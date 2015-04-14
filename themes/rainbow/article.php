<section id="page-content" class="content">
	
	<article class="post">
		<header class="post-title">
			<h1><?php echo article_title(); ?></h1>
			
			<p class="post-meta">
				Posted by <?php echo fallback(article_author(), 'admin'); ?>
				<time title datetime="<?php echo article_time(); ?>"><?php echo relative_time(article_time()); ?></time>,
				filed under <?php echo fallback(article_category(), 'uncategorised'); ?>.
			</p>
		</header>
		
		<div class="post-content">
			<?php echo article_content(); ?>
		</div>
	</article>
	
</section>