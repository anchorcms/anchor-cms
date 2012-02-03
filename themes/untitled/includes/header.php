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
		<?php echo article_css(); ?>
		<?php echo article_js(); ?>
		<?php endif; ?>
	</head>
	<body>

		<header>
			<a class="logo" href="/"><?php echo site_name(); ?></a>

			<nav class="menu" role="navigation">
				<ul>
					<?php foreach(pages() as $page): ?>
					<li <?php echo ($page->active ? 'class="active"' : ''); ?>>
						<a href="<?php echo $page->url; ?>" title="<?php echo $page->title; ?>"><?php echo $page->name; ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<form class="search" action="<?php echo search_url(); ?>" method="post">
				<input type="text" name="term" placeholder="To search, type and hit enter" value="<?php echo search_term(); ?>">
			</form>
		</header>
