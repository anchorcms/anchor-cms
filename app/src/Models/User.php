<?php

namespace Anchorcms\Models;

class User extends AbstractModel
{
    public function isActive()
    {
        return $this->status == 'active';
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getEmailEncoded()
    {
        $encoded = '';
        $len = strlen($this->email);

        for ($index = 0; $index < $len; ++$index) {
            $encoded .= '&#'.ord($this->email[$index]).';';
        }

        return $encoded;
    }
}
