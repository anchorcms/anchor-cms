<section class="content">

	<?php if(has_posts()): ?>
		<ul class="items wrap">
			<?php while(posts()): ?>
			<li>
				<a href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>">
					<?php echo article_thumbnail(); ?>
					
					<time datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time>
					
					<h2><?php echo article_title(); ?></h2>
					<p><?php echo article_description(); ?>
					<span class="category">Posted under <?php echo article_category(); ?></span>
				</a>
			</li>
			<?php endwhile; ?>
		</ul>

		<p><?php echo posts_prev(); ?> <?php echo posts_next(); ?></p>

	<?php else: ?>
		<p>Looks like you have some writing to do!</p>
	<?php endif; ?>

</section>