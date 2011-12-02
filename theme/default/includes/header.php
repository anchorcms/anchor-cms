<!doctype html>
<html lang="en">
    <head>
        <title><?php echo $this->title(); ?></title>
        <meta charset="utf-8">
        
        <meta name="description" content="<?php echo $this->get('metadata/description'); ?>">
                
        <script src="//code.jquery.com/jquery-latest.min.js"></script>
        <script src="<?php echo $this->get('theme_path'); ?>/js/site.js"></script>

		<?php if($this->get('metadata/typekit')): ?>
		<script src="//use.typekit.com/<?php echo $this->get('metadata/typekit'); ?>.js"></script>
		<?php endif; ?>
		
		<!-- This is the main style block -->
		<?php if($this->isCustom()): ?>
			<!-- Link to the custom, HTML and CSS here: -->
			<?php echo $this->getCustom()->html; ?>
		<?php else: ?>
			<link rel="stylesheet" href="<?php echo $this->get('theme_path'); ?>/css/reset.css">
			<link rel="stylesheet" href="<?php echo $this->get('theme_path'); ?>/css/style.css">
		<?php endif; ?>
    </head>
    <body class="<?php echo $this->getSlug(); ?>">
        <header id="top">
            <a id="logo" href="<?php echo $this->get('base_path'); ?>#top">
                <img src="<?php echo $this->get('theme_path'); ?>/img/logo.png" alt="<?php echo $this->get('metadata/sitename'); ?>">
            </a>
            
            <form id="search" action="/search" method="post">
                <input type="search" name="term" placeholder="Search&hellip;" value="<?php echo Search::value('term'); ?>">
                <button type="submit"><img src="<?php echo $this->get('theme_path'); ?>/img/search.gif"></button>
            </form>
        </header>
        
        <nav id="main" role="navigation">
            <ul>
                <?php foreach($this->getPages() as $page): ?>
                <li <?php echo ($page->slug == $this->getSlug() ? 'class="active"' : ''); ?>>
                    <a href="/<?php echo $page->slug; ?>" title="<?php echo $page->title; ?>"><?php echo $page->name; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
