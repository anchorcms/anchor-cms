<?php
layout('admin');

function admin_settings() {
  global $path, $urlpath;
  if (User::is_logged_in() === false) { throw403(); }
  if (isset($_POST['sitename']) === true) {
    $update = file_put_contents($path . 'config/settings.php', '<?php
    
    /******************************************************
     *
     *		General settings
     *
     ******************************************************
     *
     *		Anchor saves your site\'s settings in this
     *		file, so you can edit them here, or in the
     *		admin panel.
     */
     
    //	Site name                  What\'s your blog called?
    	$sitename = \'' . htmlentities($_POST['sitename'], ENT_QUOTES) .'\';
    
    //	Current theme        The name of the theme\'s folder
    	$theme = \'' . $_POST['theme'] .'\';
    	
    //	Clean URLs	   Can your server support mod_rewrite?
    	$clean_urls = ' . (isset($_POST['clean_urls']) ? 'true' : 'false') . ';
    	
    //	Calling home      Do you want to check for updates?
    	$callhome = ' . (isset($_POST['callhome']) ? 'true' : 'false') . ';
    
    ?>');
  }
  include $path . 'config/settings.php';
  render(array('sitename' => $sitename, 'theme' => $theme, 'clean_urls' => $clean_urls, 'callhome' => $callhome));
}

function admin_users_edit($user) {
  global $path, $urlpath;
  $user = User::find($user[1]);
  if (isset($_POST['user']) === true) {
    if ($user->update_attributes($_POST['user']) === true) {
      echo "<h1>User updated successfully</h1>";
      return;
    }
    echo "<h1>User failed to update</h1>";
  }
  render(array('user' => $user));
}

function admin_users_new() {
  global $path, $urlpath;
  if (isset($_POST['user']) === true) {
    $user = new User($_POST['user']);
    if ($user->save() === true) {
      echo "<h1>User created successfully</h1>";
      return;
    }
    echo "<h1>User failed to create</h1>";
  }
  render();
}
?>