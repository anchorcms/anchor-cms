<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo site_name(); ?></title>

		<link rel="stylesheet" href="<?php echo theme_url('css/admin.css'); ?>">
	</head>
	<body>

		<header id="top">
			<a id="logo" href="/admin">
				<img src="<?php echo theme_url('img/logo.png'); ?>" alt="Anchor CMS">
			</a>

			<?php if(user_authed() !== false): ?>
			<nav>
				<ul>
					<?php foreach(admin_menu() as $title => $url): ?>
					<li><a href="/<?php echo $url; ?>"><?php echo $title; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<?php endif; ?>
		</header>

