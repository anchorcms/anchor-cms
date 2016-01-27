<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.editing_user', $user->username); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(Auth::admin() || Auth::me($user->id)) : ?>
	<form method="post" action="<?php echo Uri::to('admin/users/edit/' . $user->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="half split">
			<p>
				<label for="label-real_name"><?php echo __('users.real_name'); ?>:</label>
				<?php echo Form::text('real_name', Input::previous('real_name', $user->real_name), array('id' => 'label-real_name')); ?>
				<em><?php echo __('users.real_name_explain'); ?></em>
			</p>
			<p>
				<label for="label-bio"><?php echo __('users.bio'); ?>:</label>
				<?php echo Form::textarea('bio', Input::previous('bio', $user->bio), array('cols' => 20, 'id' => 'label-bio')); ?>
				<em><?php echo __('users.bio_explain'); ?></em>
			</p>
			<p>
				<label for="label-status"><?php echo __('users.status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status', $user->status), array('id' => 'label-status')); ?>
				<em><?php echo __('users.status_explain'); ?></em>
			</p>
			<?php if(Auth::admin()) : ?>
			<p>
				<label for="label-role"><?php echo __('users.role'); ?>:</label>
				<?php echo Form::select('role', $roles, Input::previous('role', $user->role), array('id' => 'label-role')); ?>
				<em><?php echo __('users.role_explain'); ?></em>
			</p>
			<?php endif; ?>
		</fieldset>

		<fieldset class="half split">
			<?php foreach($fields as $field): ?>
			<p>
				<label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>
			</p>
			<?php endforeach; ?>
			<p>
				<label for="label-username"><?php echo __('users.username'); ?>:</label>
				<?php echo Form::text('username', Input::previous('username', $user->username), array('id' => 'label-username')); ?>
				<em><?php echo __('users.role_explain'); ?></em>
			</p>
			<p>
				<label for="label-password"><?php echo __('users.password'); ?>:</label>
				<?php echo Form::password('password', array('id' => 'label-password')); ?>
				<em><?php echo __('users.password_explain'); ?></em>
			</p>
			<p>
				<label for="label-email"><?php echo __('users.email'); ?>:</label>
				<?php echo Form::text('email', Input::previous('email', $user->email), array('id' => 'label-email')); ?>
				<em><?php echo __('users.email_explain'); ?></em>
			</p>
		</fieldset>
		<aside class="buttons">
			<?php echo Form::button(__('global.update'), array(
				'class' => 'btn',
				'type' => 'submit'
			)); ?>

			<?php echo Html::link('admin/users' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>

			<?php echo Html::link('admin/users/delete/' . $user->id, __('global.delete'), array('class' => 'btn delete red')); ?>
		</aside>
	</form>
	<?php else : ?>
		<p>You do not have the required privileges to modify this users information, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
		<br><a class="btn" href="<?php echo Uri::to('admin/users'); ?>">Go back</a>
	<?php endif; ?>
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
