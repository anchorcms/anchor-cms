<?php

namespace Anchorcms\Mappers;

class Users extends AbstractMapper
{
    protected $primary = 'id';

    protected $name = 'users';

    public function fetchById($id)
    {
        return $this->fetchByAttribute('id', $id);
    }

    public function fetchByUsername($name)
    {
        return $this->fetchByAttribute('username', $name);
    }

    public function fetchByEmail($email)
    {
        return $this->fetchByAttribute('email', $email);
    }
}
