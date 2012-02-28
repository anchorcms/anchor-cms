<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __('common.manage', 'Manage'); ?> <?php echo Config::get('metadata.sitename'); ?></title>

		<link rel="stylesheet" href="<?php echo theme_url('assets/css/admin.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('assets/css/popup.css'); ?>">

		<script src="<?php echo theme_url('assets/js/lib.js'); ?>"></script>
		<script src="<?php echo theme_url('assets/js/helpers.js'); ?>"></script>
		<script src="<?php echo theme_url('assets/js/popup.js'); ?>"></script>
		<script src="<?php echo theme_url('assets/js/lang.js'); ?>"></script>
		
		<script>
			//  Just bunging in my textarea thing for now
			document.ready(function() {
			
				var textareas = document.querySelectorAll('textarea');
				
				if(textareas) {
    				for(i = 0; i < textareas.length; i++) {
        				textareas[i].addEventListener('keydown', function(e) {
        					var me = this,
        					    start = me.selectionStart,
        						code = e.keyCode,
        						stop = function() {
        							e.preventDefault();
        							e.stopPropagation();
        							
        							return false;
        						},
        						actions = {
        						    9: function() { // Tab
        								me.value = me.value.slice(0, start) + '\t' + me.value.slice(start, me.value.length);
        								
        								return stop();
        							}
        						};
        						        						
        					if(actions[code]) {
        						return actions[code]();
        					} else {
        					    console.log(code);
        					}
        				});
        			}
    			}
			});
		</script>
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

