
<?php while(posts()): ?>
<article class="wrap">
	<time datetime="<?php echo article_date(); ?>">
		<?php echo article_date(); ?> in <?php echo article_category(); ?>
	</time>
	<h2><a href="<?php echo article_url(); ?>"><?php echo article_title(); ?></a></h2>
	<p>Posted in <?php echo category_title(); ?> by <?php echo author_name(); ?></p>
</article>
<?php endwhile; ?>

<?php if(has_pagination()): ?>
<p><?php echo posts_prev('Newer'); ?> <?php echo posts_next('Older'); ?></p>
<?php endif; ?>
