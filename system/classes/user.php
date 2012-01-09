<?php

//  Anchor's user helper.

class User {
    
    /**
     *    Check if there is a current user signed in
     */
    public function current() {
    
        $str = '_user';
    
        if(isset($_SESSION[$str])) {
            return $_SESSION[$str];
        } else if(isset($_COOKIE[$str])) {
            return $_COOKIE[$str];
        }
        
        return false;
    }
}