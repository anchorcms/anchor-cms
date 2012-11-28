<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.users', 'Users'); ?></h1>

	<nav>
		<?php echo Html::link(admin_url('users/add'), __('users.create_user', 'Create a new user'), array('class' => 'btn')); ?>
	</nav>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($users->results as $user): ?>
		<li>
			<a href="<?php echo admin_url('users/edit/' . $user->id); ?>">
				<strong><?php echo $user->real_name; ?></strong>
				<span>Username: <?php echo $user->username; ?></span>

				<em class="highlight"><?php echo __('users.' . $user->role); ?></em>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $users->links(); ?></aside>
</section>

<?php echo $footer; ?>