<h1>Users <a href="/admin/users/add">Create a new user</a></h1>

<?php echo notifications(); ?>

<section class="content">
	<ul class="list">
	    <?php foreach(users() as $user): ?>
	    <li>
	        <a href="<?php echo URL_PATH; ?>admin/users/edit/<?php echo $user->id; ?>">
	            <strong><?php echo $user->real_name; ?></strong>
	            <span>Username: <?php echo $user->username; ?></span>
	            
	            <i class="role"><?php echo ucwords($user->role); ?></i>
	        </a>
	    </li>
	    <?php endforeach; ?>
	</ul>
</section>