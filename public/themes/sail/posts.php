<?php while(posts()): ?>
<article>
	<time datetime="<?php echo article_date(); ?>">
		<?php echo article_date(); ?> in <?php echo article_category(); ?>
	</time>
	<h2><a href="<?php echo article_url(); ?>"><?php echo article_title(); ?></a></h2>
</article>
<?php endwhile; ?>