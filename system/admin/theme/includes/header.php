<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __('common.manage', 'Manage'); ?> <?php echo Config::get('metadata.sitename'); ?></title>

		<link rel="stylesheet" href="<?php echo theme_url('assets/css/admin.css'); ?>">
	</head>
	<body class="<?php echo Users::authed() ? 'admin' : 'login'; ?>">

		<header id="top">
		    <div class="wrap">
			<?php if(($user = Users::authed()) !== false): ?>
			<nav>
				<ul>
					<li id="logo">
						<a href="<?php echo Url::make(Config::get('application.admin_folder')); ?>">
							<img src="<?php echo theme_url('assets/img/logo.png'); ?>" alt="Anchor CMS">
						</a>
					</li>
					<?php foreach(admin_menu() as $title => $url): ?>
					<li <?php if(strpos(Url::current(), $url) !== false) echo 'class="active"'; ?>>
						<a href="<?php echo Url::make($url); ?>"><?php echo ucfirst(__('common.' . $title, $title)); ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</nav>
			
			<a class="btn" href="<?php echo admin_url('users/logout'); ?>"><?php echo __('common.logout', 'Logout'); ?></a>
			<a class="btn" href="<?php echo Url::make(); ?>"><?php echo __('common.visit_your_site', 'Visit your site'); ?></a>
			
			<!-- <div class="status-check">
				<?php if(error_check()): ?>
					<a href="#">
						<img src="<?php echo theme_url('assets/img/status-check.png'); ?>">
					</a>
					
					<ul>
						<?php foreach(error_check() as $error): ?>
						<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div> -->
			<?php else: ?>
			<a class="login" id="logo" href="<?php echo Url::make(Config::get('application.admin_folder')); ?>">
				<img src="<?php echo theme_url('assets/img/logo.png'); ?>" alt="Anchor CMS">
			</a>
			<?php endif; ?>
			</div>
		</header>

