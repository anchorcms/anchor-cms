<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __('common.manage', 'Manage'); ?> <?php echo Config::get('metadata.sitename'); ?></title>

		<link rel="stylesheet" href="<?php echo theme_url('assets/css/admin.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('assets/css/popup.css'); ?>">
	</head>
	<body>

		<header id="top">
			<a id="logo" href="<?php echo Url::make(Config::get('application.admin_folder')); ?>">
				<img src="<?php echo theme_url('assets/img/logo.png'); ?>" alt="Anchor CMS">
			</a>

			<?php if(($user = Users::authed()) !== false): ?>
			<nav>
				<ul>
					<?php foreach(admin_menu() as $title => $url): ?>
					<li <?php if(strpos(Url::current(), $url) !== false) echo 'class="active"'; ?>>
						<a href="<?php echo Url::make($url); ?>"><?php echo __('common.' . $title); ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<p><?php echo __('common.logged_in_as', 'Logged in as'); ?> <strong><?php echo $user->real_name; ?></strong>. 
			<a href="<?php echo admin_url('users/logout'); ?>"><?php echo __('common.logout', 'Logout'); ?></a></li>
			<?php endif; ?>
		</header>

