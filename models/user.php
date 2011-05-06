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
    global $db;
    
    $query = $db->prepare('SELECT * FROM users WHERE username = ?');
    $query->execute(array($this->username));
    return ($query->rowCount() = 0) ? false : true;
  }
}