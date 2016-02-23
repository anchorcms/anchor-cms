<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __('global.manage'); ?> <?php echo Config::meta('sitename'); ?></title>
		<link rel="shortcut icon" type="image/png" href="<?php echo asset('anchor/views/assets/img/favicon.png'); ?>" />

		<script src="<?php echo asset('anchor/views/assets/js/zepto.js'); ?>"></script>

		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/reset.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/admin.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/login.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/notifications.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/forms.css'); ?>">

		<link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)" href="<?php echo asset('anchor/views/assets/css/small.css'); ?>">

		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<meta name="viewport" content="width=600">
	</head>
	<body class="<?php echo Auth::guest() ? 'login' : 'admin'; ?> <?php echo str_replace('_','-',Config::app('language')); ?>">

		<header class="top">
			<div class="wrap">
				<?php if(Auth::user()): ?>
				<nav>
					<ul>
						<li class="logo">
							<a href="<?php echo Uri::to('admin/panel'); ?>">Anchor CMS</a>
						</li>

						<?php $menu = array('posts', 'comments', 'pages', 'categories', 'users', 'extend'); ?>
						<?php foreach($menu as $url): ?>
						<li <?php if(strpos(Uri::current(), $url) !== false) echo 'class="active"'; ?>>
							<a href="<?php echo Uri::to('admin/' . $url); ?>">
								<?php echo ucfirst(__($url . '.' . $url)); ?>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<?php echo Html::link('admin/logout', __('global.logout'), array('class' => 'btn')); ?>

				<?php $home = Registry::get('home_page'); ?>

				<?php echo Html::link($home->slug, __('global.visit_your_site'), array('class' => 'btn', 'target' => '_blank')); ?>

				<?php else: ?>
				<aside class="logo">
					<a href="<?php echo Uri::to('admin/login'); ?>">Anchor CMS</a>
				</aside>
				<?php endif; ?>
			</div>
		</header>
