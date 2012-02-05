<h1>Users <a href="<?php echo base_url('admin/users/add'); ?>">Create a new user</a></h1>

<?php echo notifications(); ?>

<section class="content">
	<ul class="list">
	    <?php while(users()): ?>
	    <li>
	        <a href="<?php echo base_url('admin/users/edit/' . user_id()); ?>">
	            <strong><?php echo user_real_name(); ?></strong>
	            <span>Username: <?php echo user_name(); ?></span>
	            
	            <i class="role"><?php echo ucwords(user_role()); ?></i>
	        </a>
	    </li>
	    <?php endwhile; ?>
	</ul>
</section>
