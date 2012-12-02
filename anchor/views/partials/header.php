<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __('common.manage', 'Manage'); ?> <?php echo Config::get('meta.sitename'); ?></title>

		<script src="<?php echo admin_asset('js/zepto.js'); ?>"></script>

		<link rel="stylesheet" href="<?php echo admin_asset('css/reset.css'); ?>">
		<link rel="stylesheet" href="<?php echo admin_asset('css/admin.css'); ?>">
		<link rel="stylesheet" href="<?php echo admin_asset('css/login.css'); ?>">
		<link rel="stylesheet" href="<?php echo admin_asset('css/notifications.css'); ?>">
		<link rel="stylesheet" href="<?php echo admin_asset('css/forms.css'); ?>">

		<link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)" href="<?php echo admin_asset('css/small.css'); ?>">

		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<meta name="viewport" content="width=600">
	</head>
	<body class="<?php echo Auth::guest() ? 'login' : 'admin'; ?>">

		<header class="top">
			<div class="wrap">
				<?php if(Auth::user()): ?>
				<nav>
					<ul>
						<li class="logo">
							<a href="<?php echo url('admin'); ?>">Anchor CMS</a>
						</li>

						<?php $menu = array('posts', 'comments', 'pages', 'categories', 'users', 'extend'); ?>
						<?php foreach($menu as $url): ?>
						<li <?php if(strpos(Uri::current(), $url) !== false) echo 'class="active"'; ?>>
							<a href="<?php echo admin_url($url); ?>"><?php echo ucfirst(__('common.' . $url, $url)); ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<?php echo Html::link(admin_url('logout'), __('common.logout', 'Logout'), array('class' => 'btn')); ?>

				<?php $home = Registry::get('home_page')->slug; ?>
				<?php echo Html::link($home, __('common.visit_your_site', 'Visit your site'), array('class' => 'btn', 'target' => '_blank')); ?>

				<?php else: ?>
				<aside class="logo">
					<a href="<?php echo admin_url('posts'); ?>">Anchor CMS</a>
				</aside>
				<?php endif; ?>
			</div>
		</header>