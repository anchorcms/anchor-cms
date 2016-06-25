<?php

namespace Anchorcms;

use Anchorcms\Session\StorageInterface;

/**
 * Cross-Site Request Forgery Protection
 */
class Csrf
{

    protected $session;

    public function __construct(StorageInterface $session)
    {
        $this->session = $session;
    }

    public function regenerateToken(int $length = 32)
    {
        $bytes = random_bytes($length);
        $token = bin2hex($bytes);

        $this->session->put('csrf_token', $token);
    }

    public function token(): string
    {
        if (false === $this->session->has('csrf_token')) {
            $this->regenerateToken();
        }

        return $this->session->get('csrf_token');
    }

    public function verify(string $str): bool
    {
        return hash_equals($this->token(), $str);
    }
}
