<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $urlpath; ?>admin/css.css" />
		<title><?php echo $title . ' &middot; ' . $sitename; ?></title>
	</head>
  <body class="admin">
  	<div id="header">
  		<h2><?php echo $sitename; ?></h2>
  		<?php if($loggedIn === true) { ?><a id="logout" href="<?php echo $urlpath; ?>admin/logout">Logout</a><?php } ?>
  	</div>
    <?php include $path . 'views/admin/_menu.php'; ?>
    <div id="content">
      <div id="left">
        <?php echo $content; ?>
      </div>
      <div id="right">
      	<h1>System Check</h1>
      </div>
    </div>
  </body>
</html>