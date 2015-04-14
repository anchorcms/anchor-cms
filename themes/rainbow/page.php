<section id="page-content" class="content">
	
	<article class="post">
		<header class="post-title">
			<h1><?php echo page_title(); ?></h1>
			
			<p class="post-meta">
				Posted by <?php echo fallback(page('author'), 'admin'); ?>
			</p>
		</header>
		
		<div class="post-content">
			<?php echo page_content(); ?>
		</div>
	</article>
	
</section>