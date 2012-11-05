<?php echo $header; ?>

<section class="login content">

	<?php echo $messages; ?>

	<form method="post" action="<?php echo url('amnesia'); ?>">
		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="email"><?php echo __('users.email', 'Email'); ?>:</label>
			<input autofocus placeholder="Email address" autocapitalize="off" name="email" id="email"
				value="<?php echo Input::old('email'); ?>"></p>


			<p class="buttons"><a href="<?php echo url('login'); ?>">
				<?php echo __('users.back_to', 'Back to %s', Config::get('meta.sitename')); ?></a>
			<button type="submit"><?php echo __('users.reset_password', 'Reset Password'); ?></button></p>
		</fieldset>
	</form>

</section>

<?php echo $footer; ?>