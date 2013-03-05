<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.add_user', 'Add a new user'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/users/add'); ?>" novalidate autocomplete="off">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.real_name', 'Real name'); ?>:</label>
				<?php echo Form::text('real_name', Input::previous('real_name')); ?>
			</p>

			<p>
				<label><?php echo __('users.bio', 'Biography'); ?>:</label>
				<?php echo Form::textarea('bio', Input::previous('bio'), array('cols' => 20)); ?>
			</p>

			<p>
				<label><?php echo __('users.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status')); ?>
			</p>

			<p>
				<label><?php echo __('users.role', 'Role'); ?>:</label>
				<?php echo Form::select('role', $roles, Input::previous('role')); ?>
			</p>
		</fieldset>

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.username', 'Username'); ?>:</label>
				<?php echo Form::text('username', Input::previous('username')); ?>
			</p>

			<p>
				<label><?php echo __('users.password', 'Password'); ?>:</label>
				<?php echo Form::password('password'); ?>
			</p>

			<p>
				<label><?php echo __('users.email', 'Email'); ?>:</label>
				<?php echo Form::text('email', Input::previous('email')); ?>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('users.create', 'Create'), array('class' => 'btn', 'type' => 'submit')); ?>
		</aside>
	</form>

</section>

<?php echo $footer; ?>