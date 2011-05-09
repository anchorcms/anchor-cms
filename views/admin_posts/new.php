<?php print_r(Post::create(array('slug' => '', 'blarg' => 'asda', 'title' => ''))); ?>
	<h1>Add A New Post <a href="<?php echo $urlpath; ?>admin">Cancel</a></h1>
	
	<?php include $path . 'views/admin_posts/_form.php'; ?>