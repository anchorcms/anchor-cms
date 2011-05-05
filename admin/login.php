<?php

	if(isset($_GET['logout'])) {
	  include '../core/paths.php';
	  session_start();
		unset($_SESSION['username']);
		setcookie('username', '', time() -3600);
		unset($_COOKIE['username']);
		header('location: ' . $urlpath . 'admin/login');
	}
	
//	Return the path of the main directory
	$path = substr(dirname(__FILE__), 0, -5);
	
//	Get the URL path (from http://site.com/ onwards)
//	__DIR__ - $_SERVER['DOCUMENT_ROOT']
	$urlpath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

	include($path . '/core/connect.php');
	include($path . '/core/themes.php');

	$title = 'Log In';
	
	if(isset($_POST['submit'])) {
		$find = $db->prepare('select * from users where username = :user and password = :pass');
				$find->execute(array(
					':user' => $_POST['username'],
					':pass' => md5($_POST['password'])
				));
				
		$return = $find->fetch(PDO::FETCH_OBJ);
		
		if($return !== false) {
			if(isset($_POST['remember']) && $_POST['remember'] == 'on') {
				setcookie('username', $return->username, time() + (2000 * 2000));
			} else {
				$_SESSION['username'] = $return->username;
			}
			header('Location: '. $urlpath . '/admin/index.php');
			$error = 'Logged in!';
		} else {
			$error = 'Incorrect username or password.';
		}
	}
	
	include('inc/header.php');

?>

	<div id="content">
		<div id="left">
			<h1>Log In to <?php echo $sitename; ?></h1>
			
			<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
			
			<form action="" method="post">
				<p>
					<label for="username">Username:</label>
					<input id="username" name="username" />
				</p>
				<p>
					<label for="password">Password:</label>
					<input id="password" name="password" type="password" />
				</p>
				<p>
					<label for="remember">Remember me?</label>
					<input id="remember" name="remember" type="checkbox" />
				</p>
				<p>
					<input name="submit" type="submit" value="Log In" />
				</p>
			</form>
		</div>
<?php include('inc/footer.php'); ?>