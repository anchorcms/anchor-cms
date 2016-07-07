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

        if (false === $user) {
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

    public function resetToken(ModelInterface $user)
    {
    }

    public function verifyToken(string $token, string $key)
    {
    }
}
