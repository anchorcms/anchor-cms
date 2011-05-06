<?php
$user = new User();
$loggedIn = $user->isLoggedIn();

if(strpos($_SERVER['REQUEST_URI'], 'login') == -1) {
  if ($loggedIn === false) {
		header('location: ' . $urlpath . 'admin/login');
  } else {
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
		<?php if($loggedIn === true) { ?><a id="logout" href="<?php echo $urlpath; ?>admin/logout">Logout</a><?php } ?>
	</div>