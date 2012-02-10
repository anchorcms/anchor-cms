<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo page_title("Oh no, this page can't be found"); ?> - <?php echo site_name(); ?></title>

		<meta name="description" content="<?php echo site_description(); ?>">

		<link rel="stylesheet" href="<?php echo theme_url('css/normalize.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('css/styles.css'); ?>">
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Asap">
		
		<?php if(customised()): ?>
		    <!-- Custom CSS -->
    		<style><?php echo article_css(); ?></style>
    		
    		<!--  Custom Javascript -->
    		<script><?php echo article_js(); ?></script>
		<?php endif; ?>
	</head>
	<body>

		<header>
			<a class="logo" href="<?php echo base_url(); ?>"><?php echo site_name(); ?></a>

			<?php if(has_menu_items()): ?>
			<nav class="menu" role="navigation">
				<ul>
					<?php while(menu_items(array('sortby' => 'name'))): ?>
					<li <?php echo (menu_active() ? 'class="active"' : ''); ?>>
						<a href="<?php echo menu_url(); ?>" title="<?php echo menu_title(); ?>">
							<?php echo menu_name(); ?>
						</a>
					</li>
					<?php endwhile; ?>
				</ul>
			</nav>
			<?php endif; ?>

			<form class="search" action="<?php echo search_url(); ?>" method="post">
				<input type="text" name="term" placeholder="To search, type and hit enter" value="<?php echo search_term(); ?>">
			</form>
		</header>
