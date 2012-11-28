<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.editing', 'Editing'); ?>
	<?php echo $user->username; ?>&rsquo;s <?php echo __('users.profile', 'profile'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('users/edit/' . $user->id); ?>" novalidate autocomplete="off">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.real_name', 'Real name'); ?>:</label>
				<?php echo Form::text('real_name', Input::old('real_name', $user->real_name)); ?>
			</p>

			<p>
				<label><?php echo __('users.bio', 'Biography'); ?>:</label>
				<?php echo Form::textarea('bio', Input::old('bio', $user->bio), array('cols' => 20)); ?>
			</p>

			<p>
				<label><?php echo __('users.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::old('status', $user->status)); ?>
			</p>

			<p>
				<label><?php echo __('users.role', 'Role'); ?>:</label>
				<?php echo Form::select('role', $roles, Input::old('role', $user->role)); ?>
			</p>
		</fieldset>

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.username', 'Username'); ?>:</label>
				<?php echo Form::text('username', Input::old('username', $user->username)); ?>
			</p>

			<p>
				<label><?php echo __('users.password', 'Password'); ?>:</label>
				<?php echo Form::password('password'); ?>
			</p>

			<p>
				<label><?php echo __('users.email', 'Email'); ?>:</label>
				<?php echo Form::text('email', Input::old('email', $user->email)); ?>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('users.update', 'Update'), array(
				'class' => 'btn',
				'type' => 'submit'
			)); ?>

			<?php echo Html::link(admin_url('users/delete/' . $user->id), __('users.delete', 'Delete'), array(
				'class' => 'btn delete red'
			)); ?>
		</aside>
	</form>

</section>

<?php echo $footer; ?>