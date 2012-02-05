<section class="content">
	<h3><?php echo page_title(); ?></h3>

	<?php echo page_content(); ?>
	
	<?php if(user_authed()): ?>
	<p class="footnote"><a href="admin/pages/edit/<?php echo page_id(); ?>">Edit this page</a></p>
	<?php endif; ?>
</section>

