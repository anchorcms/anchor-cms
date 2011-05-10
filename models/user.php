<?php
$current_user = null;
class User extends ActiveRecord\Model {
  public function is_logged_in() {
    global $current_user;
    if (isset($_SESSION['username']) === false) { $current_user = false; }
    if (isset($current_user) === false) {
      $current_user = User::find_by_username($_SESSION['username']);
      if (isset($current_user) === false) { $current_user = false; }
    }
    return $current_user !== false;
  }
  
  public function login($username = null, $password = null, $remember_me = null) {
    if (empty($username) === true && isset($_POST['username']) === true) { $username = $_POST['username']; }
    if (empty($password) === true && isset($_POST['password']) === true) { $password = $_POST['password']; }
    if (empty($username) === true || empty($password) === true) {
      return false;
    }
    
    if (isset($remember_me) === false || isset($_POST['remember_me']) === true) { $remember_me = true; }
    
    if (User::exists(array('username' => $username, 'password' => md5($password))) === true) {
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
}
?>