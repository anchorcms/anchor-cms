<?php

namespace Anchorcms\Services;

use Anchorcms\Mappers\MapperInterface;
use Anchorcms\Models\ModelInterface;

class Auth
{
    protected $options = ['cost' => 14];

    protected $dummyHash;

    public function __construct()
    {
        $string = bin2hex(random_bytes(64));
        $this->dummyHash = $this->hashPassword($string);
    }

    public function login(MapperInterface $users, string $username, string $password)
    {
        // check username
        $user = $users->fetchByUsername($username);

        if (false === $user || $user->status != 'active') {
            // protected against user enumeration
            $this->verifyPassword($password, $this->dummyHash);

            return false;
        } elseif ($this->verifyPassword($password, $user->password)) {
            return $user;
        }

        return false;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT, $this->options);
    }

    public function checkPasswordHash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT, $this->options);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function changePassword(MapperInterface $users, int $user, string $password)
    {
        $users->update($user, [
            'password' => $this->hashPassword($password),
        ]);
    }

    protected function purgeTokens(MapperInterface $tokens)
    {
        $now = new \DateTime;

        $query = $tokens->andWhere('expires < :expires')
            ->setParameter('expires', $now->format('Y-m-d H:i:s'))
        ;

        foreach ($tokens->fetchAll($query) as $token) {
            $tokens->delete($token->id);
        }
    }

    protected function splitToken(string $token): array
    {
        return [
            substr($token, 0, 32),
            hash('sha256', substr($token, 32)),
        ];
    }

    public function resetToken(ModelInterface $user, MapperInterface $tokens, \DateTimeInterface $expires): string
    {
        $token = bin2hex(random_bytes(32));
        list($partToken, $signedToken) = $this->splitToken($token);

        $tokens->insert([
            'user' => $user->id,
            'expires' => $expires->format('Y-m-d H:i:s'),
            'token' => $partToken,
            'signature' => $signedToken,
        ]);

        return $token;
    }

    /**
     * Checks token and returns the user ID if found or zero
     *
     * @param object
     * @param string
     * @return int
     */
    public function verifyToken(MapperInterface $tokens, string $token): int
    {
        $this->purgeTokens($tokens);

        list($partToken, $signedToken) = $this->splitToken($token);

        $now = new \DateTime;

        $query = $tokens->andWhere('token = :token')
            ->setParameter('token', $partToken)
            ->andWhere('signature = :signature')
            ->setParameter('signature', $signedToken)
            ->andWhere('expires > :expires')
            ->setParameter('expires', $now->format('Y-m-d H:i:s'))
        ;

        $result = $tokens->fetch($query);

        // not found
        if (! $result) {
            return 0;
        }

        // purge from db
        $tokens->delete($result->id);

        return $result->user;
    }
}
