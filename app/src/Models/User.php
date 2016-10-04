<?php

namespace Anchorcms\Models;

/**
 * Class User.
 * @package Anchorcms\Models
 */
class User extends AbstractModel
{
    /**
     * whether a user is active
     *
     * @access public
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 'active';
    }

    /**
     * gets the user name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * gets the users email address
     *
     * @access public
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * gets the users email address as an encoded string
     *
     * @access public
     * @return string
     */
    public function getEmailEncoded()
    {
        $encoded = '';
        $len = strlen($this->email);

        for ($index = 0; $index < $len; ++$index) {
            $encoded .= '&#' . ord($this->email[$index]) . ';';
        }

        return $encoded;
    }
}
