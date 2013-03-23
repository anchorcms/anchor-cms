<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.add_user'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/users/add'); ?>" novalidate autocomplete="off">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.real_name'); ?>:</label>
				<?php echo Form::text('real_name', Input::previous('real_name')); ?>
				<em><?php echo __('users.real_name_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.bio'); ?>:</label>
				<?php echo Form::textarea('bio', Input::previous('bio'), array('cols' => 20)); ?>
				<em><?php echo __('users.bio_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status')); ?>
				<em><?php echo __('users.status_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.role'); ?>:</label>
				<?php echo Form::select('role', $roles, Input::previous('role')); ?>
				<em><?php echo __('users.role_explain'); ?></em>
			</p>
		</fieldset>

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.username'); ?>:</label>
				<?php echo Form::text('username', Input::previous('username')); ?>
				<em><?php echo __('users.role_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.password'); ?>:</label>
				<?php echo Form::password('password'); ?>
				<em><?php echo __('users.password_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.email'); ?>:</label>
				<?php echo Form::text('email', Input::previous('email')); ?>
				<em><?php echo __('users.email_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.create'), array('class' => 'btn', 'type' => 'submit')); ?>
		</aside>
	</form>

</section>

<?php echo $footer; ?>