<?php
/******************************************************
 *
 *              users.php
 *
 ******************************************************
 *
 *              Retrieve information about users 
 */

class User {
  public  $username = '';
  private $password = '';

  function __construct($username = '', $password = '') {
    $this -> username = $username;
    $this -> password = $password;
  }

  function isLoggedIn() {
    return (isset($_SESSION['username']) || isset($_COOKIE['username']));
  }

  function exists() {
    //  I'll rewrite this with PDO at some point.
    //  For now, though, it's just good old MySQL.
    include('paths.php');
    include($path.'/config/database.php');
    $link = @mysql_connect($host, $user, $pass);
    @mysql_select_db($name);
    return @mysql_num_rows(@mysql_query("SELECT * FROM `users` WHERE `username` = '$this->username'", $link));
  }
}