<?php

//  Anchor's user helper.

class User {
    
    /**
     *    Check if there is a current user signed in
     */
    public function current($str = '_user') {

        if(isset($_SESSION[$str])) {
            return $_SESSION[$str];
        } else if(isset($_COOKIE[$str])) {
            return json_decode($_COOKIE[$str]);
        }
        
        return false;
    }
    
    public function login($name, $value, $cookie = true) {
        //  Are we setting a cookie?
        if($cookie === true) {
            return setcookie($name, json_encode($value), strtotime('+1 year'));
        }
    
        return $_SESSION[$name] = $value;
    }
    
    public function logout($name = '_user') {
        if(isset($_SESSION[$str])) unset($_SESSION[$str]);
        if(isset($_COOKIE[$str])) unset($_COOKIE[$str]);
        
        return isset($_SESSION[$str]);
    }
    
    public function verify($user, $pass) {
        $query = $this->db->fetch('', 'users', array('username' => $user, 'password' => $pass));
        
        if(count($query) === 1) {
        
            self::login('_user', $query);
        
            return $query;
        }
        
        return false;
    }
    
    public function encrypt($str) {
       return crypt($str, '$2a$07$erg340r09ssg24g0w0dvq4mr6$');
    }
}