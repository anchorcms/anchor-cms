<h1><?php echo __('users.users', 'Users'); ?> 
<a href="<?php echo admin_url('users/add'); ?>"><?php echo __('users.create_user', 'Create a new user'); ?></a></h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<ul class="list">
	    <?php foreach($users as $user): ?>
	    <li>
	        <a href="<?php echo admin_url('users/edit/' . $user->id); ?>">
	            <strong><?php echo $user->real_name; ?></strong>
	            <span>Username: <?php echo $user->username; ?></span>
	            
	            <i class="role"><?php echo __('users.' . $user->role); ?></i>
	        </a>
	    </li>
	    <?php endforeach; ?>
	</ul>
</section>
