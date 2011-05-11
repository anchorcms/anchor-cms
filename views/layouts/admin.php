<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $urlpath; ?>stylesheets/admin.css" />
		<title>random &middot; random</title>
	</head>
  <body class="admin">
  	<div id="header">
  		<h2>random</h2>
  		<?php if(User::is_logged_in() === true) { ?><a id="logout" href="<?php echo $urlpath; ?>admin/logout">Logout</a><?php } ?>
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