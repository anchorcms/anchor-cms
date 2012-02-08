<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Manage <?php echo site_name(); ?></title>

		<link rel="stylesheet" href="<?php echo theme_url('assets/css/admin.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('assets/css/popup.css'); ?>">
	</head>
	<body>

		<header id="top">
			<a id="logo" href="<?php echo base_url(Config::get('application.admin_folder')); ?>">
				<img src="<?php echo theme_url('assets/img/logo.png'); ?>" alt="Anchor CMS">
			</a>

			<?php if(user_authed() !== false): ?>
			<nav>
				<ul>
					<?php foreach(admin_menu() as $title => $url): ?>
					<li <?php if(strpos(menu_url(), $url) !== false) echo 'class="active"'; ?>>
						<a href="<?php echo base_url($url); ?>"><?php echo $title; ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<p>Logged in as <strong><?php echo user_authed_realname(); ?></strong>. 
			<a href="<?php echo base_url('admin/users/logout'); ?>">Logout</a></li>
			<?php endif; ?>
		</header>

