
<section class="content">
	<p><strong><?php echo article_title(); ?></strong></p>
	<p>Posted <?php echo relative_time(article_time()); ?> by <?php echo article_author(); ?></p>
	
	<?php echo article_html(); ?>

	<?php if(user_authed()): ?>
	<p class="edit">
		<a href="<?php echo admin_url('posts/edit/' . article_id()); ?>">Edit this article</a>
		<?php if(page_status() != 'published'): ?>
		<br>Your curretly viewing this post as <strong><?php echo article_status(); ?></strong>
		<?php endif; ?>
	</p>
	<?php endif; ?>

</section>

<section class="content">
	<p><strong>About the author</strong></p>
	<p><?php echo article_author_bio(); ?></p>
</section>

<?php if(comments_open()): ?>

<?php if(has_comments()): ?>
<section class="content">
	<p><strong>Comments</strong></p>

	<?php if(user_authed()): ?>
	<p class="edit"><a href="<?php echo admin_url('posts/edit/' . article_id() . '#comments'); ?>">Moderate comments</a></p>
	<?php endif; ?>
	
	<?php while(comments()): ?>
	<p><strong><?php echo comment_name(); ?> posted <?php echo relative_time(comment_time()); ?></strong></p>
	<p><?php echo comment_text(); ?></p>
	<?php endwhile; ?>
</section>
<?php endif; ?>

<section class="content">
	<form method="post" action="<?php echo current_url(); ?>">
		<legend>Add your comments</legend>
		
		<?php echo comment_form_notifications(); ?>
		
		<p><label>Name<br>
		<?php echo comment_form_input_name(); ?></label></p>
		
		<p><label>Email<br>
		<em>Your email address will never be published</em><br>
		<?php echo comment_form_input_email(); ?></label></p>
		
		<p><label>Comments<br>
		<?php echo comment_form_input_text(); ?></label></p>
		
		<p><?php echo comment_form_button(); ?></p>
	</form>
</section>

<?php endif; ?>


