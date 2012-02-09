
<?php if(has_search_results()): ?>
<section class="content">
	<p>We found <?php echo total_search_results(); ?> <?php echo pluralise(total_search_results(), 'result'); ?> 
	for &ldquo;<?php echo search_term(); ?>&rdquo;.</p>
</section>
<ul class="posts">
	<?php while(search_results()): ?>
	<li>
		<h3><?php echo article_title(); ?></h3>
		<p><?php echo article_description(); ?></p>
		
		<?php if(user_authed()): ?>
		<p><a  class="quiet" href="<?php echo admin_url('posts/edit/' . article_id()); ?>">Edit this article</a></p>
		<?php endif; ?>
		
		<p><a class="btn" href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>">Continue Reading</a></p>
	</li>
	<?php endwhile; ?>
</ul>
<?php else: ?>
<section class="content">
	<p>Unfortunately, there's no results for &ldquo;<?php echo search_term(); ?>&rdquo;.</p>
</section>
<?php endif; ?>

