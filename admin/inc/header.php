<?php

	include($path . '/core/users.php');	
	if(!strpos($_SERVER['REQUEST_URI'], 'login')) {
		if(isset($_SESSION['username']) || isset($_COOKIE['username'])) {
			$loggedin = true;
		} else {
			header('location: ' . $urlpath . 'admin/login');
		}
	} else {
		if(isset($_SESSION['username']) || isset($_COOKIE['username'])) {
			header('location: ' . $urlpath . 'admin');
		}
	}
	
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $urlpath; ?>admin/css.css" />
		<title><?php echo $title . ' &middot; ' . $sitename; ?></title>
	</head>
</html>
<body class="admin">
	<div id="header">
		<h2><?php echo $sitename; ?></h2>
		<?php if(isset($loggedin) && $loggedin === true) { ?><a id="logout" href="<?php echo $urlpath; ?>admin/logout">Logout</a><?php } ?>
	</div>