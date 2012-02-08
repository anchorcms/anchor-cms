
<section class="content highlight">
	<?php echo page_content(); ?>
	
	<?php if(user_authed()): ?>
	<p class="footnote"><a href="<?php echo base_url('admin/pages/edit/' . page_id()); ?>">Edit this page</a></p>
	<?php endif; ?>
</section>

<?php if(has_posts()): ?>
<ul class="posts">
	<?php while(posts()): ?>
	<li>
		<h3><?php echo article_title(); ?></h3>
		<p>Posted <?php echo relative_time(article_time()); ?> by <?php echo article_author(); ?></p>
		<p><?php echo article_description(); ?></p>
		
		<?php if(user_authed()): ?>
		<p><a  class="quiet" href="<?php echo base_url('admin/posts/edit/' . article_id()); ?>">Edit this article</a></p>
		<?php endif; ?>
		
		<p><a class="btn" href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>">Continue Reading</a></p>
	</li>
	<?php endwhile; ?>
</ul>
<?php else: ?>
<section class="content">
	<p>Looks like you have some writing to do!</p>
</section>
<?php endif; ?>

