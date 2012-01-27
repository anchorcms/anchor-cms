<!doctype html>
<html lang="en">
    <head>
        <title><?php echo $this->title(); ?></title>
        <meta charset="utf-8">
        
        <meta name="description" content="<?php echo $this->get('metadata/description'); ?>">
		
		<link rel="stylesheet" href="<?php echo $this->get('theme_path'); ?>/css/reset.css">
		<link rel="stylesheet" href="<?php echo $this->get('theme_path'); ?>/css/style.css">
		
		<link rel="stylesheet" media="only screen and (max-width: 1150px)" href="<?php echo $this->get('theme_path'); ?>/css/smaller.css">
		
		<!-- This is the main style block -->
		<?php if($this->isCustom()) echo $this->getCustom()->html; ?>
    </head>
    <body class="<?php echo $this->getSlug() . ($this->isCustom() ? ' custom' : '') ?>">
    
        <header id="top">
            <a id="logo" href="<?php echo $this->get('base_path'); ?>#top">
                <?php echo $this->get('metadata/sitename'); ?>
            </a>
            
            <nav id="main" role="navigation">
                <ul>
                    <?php foreach($this->getPages() as $page): ?>
                    <li <?php echo ($page->slug === $this->getSlug() ? 'class="active"' : ''); ?>>
                        <a href="/<?php echo $page->slug; ?>" title="<?php echo $page->title; ?>"><?php echo $page->name; ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            
            <form id="search" action="/search" method="post">
                <input type="search" name="term" placeholder="To search, type and hit enter&hellip;" value="<?php echo Search::value('term'); ?>">
            </form>
        </header>