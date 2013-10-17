<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		<title><?php echo __('global.manage'); ?> <?php echo Config::meta('sitename'); ?></title>

		<link rel="shortcut icon" href="<?php echo asset('anchor/views/assets/img/favicon.ico'); ?>">

		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/styles.css'); ?>">
		
		<link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)" href="<?php echo asset('anchor/views/assets/css/small.css'); ?>">
		
		<script src="<?php echo asset('anchor/views/assets/js/zepto.js'); ?>"></script>
		
	</head>
	<body class="<?php echo Auth::guest() ? 'login' : 'admin'; ?>">
	
        <!-- Add loading class for preloader -->
        <script>document.body.className += ' js loading';</script> 
	
	<div class="star">
		<header class="top">
			<div class="wrap">
				<?php if(Auth::user()): ?>
				<nav>
					<ul>
						<?php $menu = array('posts' => 'feather-1', 'comments' => 'writing-comment-2', 'pages' => 'multiple-documents-1', 'menu' => 'menu-list-4', 'categories' => 'tag-1', 'users' => 'user-1', 'extend' => 'cube-1'); ?>
						<?php foreach($menu as $url => $class): ?>
						<li <?php if(strpos(Uri::current(), $url) !== false) echo 'class="active"'; ?>>
							<a class="flaticon <?php echo $class; ?>" href="<?php echo Uri::to('admin/' . $url); ?>">
								<?php echo ucfirst(__($url . '.' . $url)); ?>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<?php echo Html::link('admin/logout',
				    '<span title="' . __('global.logout') . '">&#xe510;</span>',
				    array('class' => 'bottom')
				); ?>
				<?php echo Html::link(Registry::get('home_page')->slug,
				    '<span title="' . __('global.visit_your_site') . '">&#xe45e;</span>',
				    array('class' => 'bottom', 'target' => '_blank')
				); ?>

				<?php else: ?>
				<aside class="logo">
					<a href="<?php echo Uri::to('admin/login'); ?>">Anchor CMS</a>
				</aside>
				<?php endif; ?>
			</div>
		</header>