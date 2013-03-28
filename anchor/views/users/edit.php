<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.editing_user', $user->username); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/users/edit/' . $user->id); ?>" novalidate autocomplete="off">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.real_name'); ?>:</label>
				<?php echo Form::text('real_name', Input::previous('real_name', $user->real_name)); ?>
				<em><?php echo __('users.real_name_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.bio'); ?>:</label>
				<?php echo Form::textarea('bio', Input::previous('bio', $user->bio), array('cols' => 20)); ?>
				<em><?php echo __('users.bio_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status', $user->status)); ?>
				<em><?php echo __('users.status_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.role'); ?>:</label>
				<?php echo Form::select('role', $roles, Input::previous('role', $user->role)); ?>
				<em><?php echo __('users.role_explain'); ?></em>
			</p>
		</fieldset>

		<fieldset class="half split">
			<p>
				<label><?php echo __('users.username'); ?>:</label>
				<?php echo Form::text('username', Input::previous('username', $user->username)); ?>
				<em><?php echo __('users.role_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.password'); ?>:</label>
				<?php echo Form::password('password'); ?>
				<em><?php echo __('users.password_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('users.email'); ?>:</label>
				<?php echo Form::text('email', Input::previous('email', $user->email)); ?>
				<em><?php echo __('users.email_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.update'), array(
				'class' => 'btn',
				'type' => 'submit'
			)); ?>

			<?php echo Html::link('admin/users/delete/' . $user->id, __('global.delete'), array('class' => 'btn delete red')); ?>
		</aside>
	</form>

</section>

<?php echo $footer; ?>