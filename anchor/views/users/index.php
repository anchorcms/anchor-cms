<?php echo $header; ?>

<h1><?php echo __('users.users', 'Users'); ?>
<a href="<?php echo url('users/add'); ?>"><?php echo __('users.create_user', 'Create a new user'); ?></a></h1>

<?php echo $messages; ?>

<section class="content">
	<ul class="list">
		<?php foreach($users->results as $user): ?>
		<li>
			<a href="<?php echo url('users/edit/' . $user->id); ?>">
				<strong><?php echo $user->real_name; ?></strong>
				<span>Username: <?php echo $user->username; ?></span>

				<em class="highlight"><?php echo __('users.' . $user->role); ?></em>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $users->links(); ?>
</section>

<?php echo $footer; ?>