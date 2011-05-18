	<h1>Edit A User <a href="<?php echo $urlpath; ?>admin/users">Cancel</a></h1>
	
<?php render(array('view' => 'admin_users/_form', 'user' => $user)); ?>