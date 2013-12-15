<?php echo $header; ?>

<section class="login content">

	<?php echo $messages; ?>
	<?php $user = filter_var(Input::previous('user'), FILTER_SANITIZE_STRING); ?>

	<form method="post" action="<?php echo Uri::to('admin/login'); ?>">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="user"><?php echo __('users.username'); ?>:</label>
			<?php echo Form::text('user', $user, array(
				'id' => 'user',
				'autocapitalize' => 'off',
				'autofocus' => 'true',
				'placeholder' => __('users.username')
			)); ?></p>

			<p><label for="pass"><?php echo __('users.password'); ?>:</label>
			<?php echo Form::password('pass', array(
				'id' => 'pass',
				'placeholder' => __('users.password')
			)); ?></p>

			<p class="buttons"><a href="<?php echo Uri::to('admin/amnesia'); ?>"><?php echo __('users.forgotten_password'); ?></a>
			<button type="submit"><?php echo __('global.login'); ?></button></p>
		</fieldset>
	</form>

</section>

<?php echo $footer; ?>