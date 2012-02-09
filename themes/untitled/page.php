
<section class="content">
	<h3><?php echo page_title(); ?></h3>

	<?php echo page_content(); ?>
	
	<?php if(user_authed()): ?>
	<p class="footnote"><a href="<?php echo admin_url('pages/edit/' . page_id()); ?>">Edit this page</a></p>
	<?php endif; ?>
</section>

