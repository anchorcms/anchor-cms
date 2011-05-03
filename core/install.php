<?php

/******************************************************
 *
 *		install.php						by @visualidiot
 *
 ******************************************************
 *
 *		The installer for Anchor. Will run if the
 *		config file is not correctly set up.
 */
 
	$step = 'one';

//	Database fields	
	$i_host = trim($_POST['host']);
	$i_user = trim($_POST['user']);
	$i_pass = trim($_POST['pass']);
	$i_name = trim($_POST['name']);
	$i_sitename = trim($_POST['sitename']);
	$i_theme = trim($_POST['theme']);
	
	$auser = $_POST['auser'];
	$apass = $_POST['apass'];
	
	if($_POST['clean'] == 'on') {
		$i_clean = 'true';
	} else {
		$i_clean = 'false';
	}
	
	
	include($path . '/config/database.php');
	if($host && $user && $pass && $name) {
		$step = 'two';
	}
	
	if($step == 'one') {
		if(@mysql_connect($i_host, $i_user, $i_pass) && @mysql_select_db($i_name)) {
			$connect = true;
			file_put_contents($path . '/config/database.php', '<?php
	
	/******************************************************
	 *
	 *		Database settings
	 *
	 ******************************************************
	 *
	 *		Anchor requires a MySQL database to function
	 *		properly. If you\'re not sure about any details,
	 *		check with your host for more information.
	 */
	 
	//	DB host                  This is probably localhost
		$host = \'' . $i_host . '\';
	
	//	DB database name           The name of the database
		$name = \'' . $i_name . '\';
	
	//	DB username                  Your database username
		$user = \'' . $i_user . '\';
	
	//	DB password       Shouldn\'t be your Anchor password
		$pass = \''. $i_pass . '\';
	
	?>');
	
			$step = 'two';
			
			//	Insert the tables.
			$create = mysql_query('
				CREATE TABLE IF NOT EXISTS `posts` (
					`id` TINYINT NOT NULL AUTO_INCREMENT,
					`slug` VARCHAR(50) NOT NULL,
					`title` VARCHAR(256) NOT NULL,
					`excerpt` TEXT NOT NULL,
					`content` TEXT NOT NULL,
					`css` VARCHAR(256) NOT NULL,
					`javascript` VARCHAR(256) NOT NULL,
					`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				);
				CREATE TABLE IF NOT EXISTS `users` (
					`id` TINYINT NOT NULL AUTO_INCREMENT,
					`username` VARCHAR(32) NOT NULL ,
					`password` VARCHAR(32) NOT NULL ,
					`display` VARCHAR(150) NOT NULL ,
					`date` TIMESTAMP NOT NULL ,
					PRIMARY KEY (`id`)
				);
			');
			
			if(!$create) { $error = 'Couldn\'t create the tables needed. Run the SQL in <code>install.txt</code>, please.'; }
			mysql_close();
		} else {
			$error = 'Could not connect to database with that information.';
		}
	}
	
	if($step == 'two') {
		include($path . '/config/database.php');
		if($i_sitename) {
			$settings = file_put_contents($path . '/config/settings.php', '<?php

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
	$sitename = \'' . $i_sitename .'\';

//	Current theme        The name of the theme\'s folder
	$theme = \'' . $i_theme .'\';
	
//	Clean URLs	   Can your server support mod_rewrite?
	$clean_urls = ' . $i_clean . ';
	
//	Calling home      Do you want to check for updates?
	$callhome = true;

?>');
			if($settings) {
				$step = 'three';
			} else {
				$error .= 'Could not write to the settings file.';
			}
		} else {
			$error .= 'Please enter a site name.';
		}
	}
?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="admin/css.css" />
		<title>Install Anchor CMS (in two steps)</title>
	</head>
</html>
<body class="install">
	<div id="header">
		<h2>Install Anchor</h2>
	</div>
	<div id="content">
		<div id="left">
			<h1>Hi there, Sailor! <span style="font-weight: lighter; float: right; padding-right: 50px;">(Step <?php echo ucwords($step); ?>)</span></h1>
			<?php if(isset($_POST['submit']) && $error) { echo '<p id="error">' . $error . '</p>'; } ?>
			<?php if($step == 'one') { ?>
				<form method="post" action="">
					<h3>Database details</h3>
					<p>
						<label for="host">Database host:</label>
						<input id="host" name="host" placeholder="localhost" value="<?php echo $i_host; ?>" />
					</p>
					<p>
						<label for="user">Database username:</label>
						<input id="user" name="user" placeholder="username" value="<?php echo $i_user; ?>" />
					</p>
					<p>
						<label for="pass">Database password:</label>
						<input id="pass" name="pass" placeholder="password" value="<?php echo $i_pass; ?>" />
					</p>
					<p>
						<label for="name">Database name:</label>
						<input id="name" name="name" value="<?php echo $i_name; ?>" />
					</p>
					<p>
						<input type="submit" value="Next Step" name="submit" />
					</p>
				</form>	
			<?php } else if($step == 'two') { ?>
				<form method="post" action="">
					<h3>Personal settings</h3>
					<p>
						<label for="sitename">Site name:</label>
						<input id="sitename" name="sitename" placeholder="My Awesome Anchor Site" value="<?php echo $i_sitename; ?>" />
					</p>
					<p>
						<label for="theme">Theme:</label>
						<select name="theme">
						<?php
						if($dh = opendir($path . '/themes')) {
					        while(($file = readdir($dh)) !== false) {
					            if(($file != '.') && ($file != '..')) {
					            	$title = str_replace('_', ' ', $file);
					            	$title = ucwords($title);
					            	if($file == 'default') { $default = ' selected'; }
					            	echo '<option value="' . strtolower($file) . '"'. $default .'>' . $title . '</option>';
					            }
					        }
					        closedir($dh);
					    }
						?>
						</select>
					</p>
					<p>
						<label for="clean">Clean URLs?</label>
						<input id="clean" name="clean" type="checkbox" value="true" />
						
					</p>
					
					<p>
						<label for="auser">Admin username:</label>
						<input id="auser" name="auser" placeholder="Username" />
					</p>

					<p>
						<label for="apass">Admin password:</label>
						<input id="apass" type="password" name="apass" placeholder="Password" />
					</p>

					<p>
						<input type="submit" value="Finish!" name="submit" />
					</p>
				</form>			
			<?php
			} else if($step == 'three') {
			    $message = '<!doctype html>
			<html>
			<head>
				<title>Your Anchor CMS install went A-OK!</title>
				<style>
					* { -webkit-font-smoothing: antialiased; }
					body { font-family: \'Helvetica Neue\', sans-serif; color: #999; line-height: 20px; font-size: 11px; }
					a { text-decoration: underline; }
					a:hover { text-decoration: none; }
				</style>
			</head>
			<body style="font-family: \'Helvetica Neue\', sans-serif; color: #999; line-height: 20px; font-size: 11px;">
				<p align="center"><img src="http://anchorcms.com/_/img/title.png" /></p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>Anchor installed great! For reference, here\'s your username and password:</p>
				<p><strong>Username:</strong> ' . $auser . '</p>
				<p><strong>Password:</strong> Whatever you chose in the install.</p>
				<p>Thanks,<br />The Anchor CMS Crew</p>
			</body>
			</html>'; 
			
			    $headers  = "MIME-Version: 1.0\r\n"; 
			    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			    $headers .= "From: Anchor CMS <hi@anchorcms.com>\r\n"; 
			     
			    mail($email, 'Anchor CMS installed A-OK!', $message, $headers); 

				mysql_connect($host, $user, $pass);
				mysql_select_db($name);
				$query = mysql_query("INSERT INTO `users` (`id`, `username`, `password`, `display`, `date`) VALUES (NULL, '$auser', MD5('$apass'), 'Administrator', CURRENT_TIMESTAMP);") or die(mysql_error());

				$finish = file_put_contents('core/check.php', '<?php $installed = true; ?>');
				if($finish && $query) { header('location: ' . $urlpath); } else { $error .= 'MySQL or filewrite error.'; }
			} ?>	
		</div>
		<?php include('admin/inc/footer.php'); ?>