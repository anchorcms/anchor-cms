
<section class="content">
	<?php echo page_content(); ?>

	<?php if(user_authed()): ?>
	<p class="edit">
		<a href="<?php echo admin_url('pages/edit/' . page_id()); ?>">Edit this page</a>
		<?php if(page_status() != 'published'): ?>
		<br>Your curretly viewing this page as <strong><?php echo page_status(); ?></strong>
		<?php endif; ?>
	</p>
	<?php endif; ?>
</section>

