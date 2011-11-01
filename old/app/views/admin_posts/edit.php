	<h1>Edit A Post <a href="<?php echo $urlpath; ?>admin">Cancel</a></h1>
	
 <?php render(array('view' => 'admin_posts/_form', 'post' => $post)); ?>