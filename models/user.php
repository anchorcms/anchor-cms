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

  function is_logged_in() {
    return isset($_SESSION['username']);
  }
  
  function login($username = null, $password = null, $remember_me = null) {
    if (empty($username) === true && isset($_POST['username']) === true) { $username = $_POST['username']; }
    if (empty($password) === true && isset($_POST['password']) === true) { $password = $_POST['password']; }
    if (empty($username) === true || empty($password) === true) {
      return false;
    }
    
    if (isset($remember_me) === false || isset($_POST['remember_me']) === true) { $remember_me = true; }
    
    global $db;
    
    $query = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    $query->execute(array($username, md5($password)));
    
    if ($query->rowCount() != 0) {
      if($remember_me === true) {
      	setcookie('username', $username, time() + (86400 * 7));
      }
      session_destroy();
      session_start();
      $_SESSION['username'] = $username;
      return true;
    }
    return false;
  }

  function exists() {
    global $db;
    
    $query = $db->prepare('SELECT * FROM users WHERE username = ?');
    $query->execute(array($this->username));
    return ($query->rowCount() == 0) ? false : true;
  }
}