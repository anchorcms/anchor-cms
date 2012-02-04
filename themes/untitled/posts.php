
<section class="content highlight">
	<?php echo page_content(); ?>
	
	<?php if(user_authed()): ?>
	<p class="footnote"><a href="/admin/pages/edit/<?php echo page_id(); ?>">Edit this page</a></p>
	<?php endif; ?>
</section>

<?php if(has_posts()): ?>
<ul class="posts">
	<?php while(posts()): ?>
	<li>
		<h3><?php echo post_title(); ?></h3>
		<p>Posted <?php echo relative_time(post_time()); ?> by <?php echo post_author(); ?></p>
		<p><?php echo post_description(); ?></p>
		
		<?php if(user_authed()): ?>
		<p><a  class="quiet" href="/admin/posts/edit/<?php echo post_id(); ?>">Edit this article</a></p>
		<?php endif; ?>
		
		<p><a class="btn" href="<?php echo post_url(); ?>" title="<?php echo post_title(); ?>">Continue Reading</a></p>
	</li>
	<?php endwhile; ?>
</ul>
<?php else: ?>
<section class="content">
	<p>Looks like you have some writing to do!</p>
</section>
<?php endif; ?>

