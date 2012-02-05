
<section class="content">
	<h3><?php echo page_title(); ?></h3>

	<?php echo page_content(); ?>
	
	<?php if(user_authed()): ?>
	<p class="footnote"><a href="<?php echo base_url('admin/pages/edit/' . page_id()); ?>">Edit this page</a></p>
	<?php endif; ?>
</section>

