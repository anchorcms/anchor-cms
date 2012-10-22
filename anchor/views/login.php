<?php echo $header; ?>

<?php echo $messages; ?>

<section class="login content">

	<form method="post" action="<?php echo url('login'); ?>">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="user"><?php echo __('users.username', 'Username'); ?>:</label>
			<input autofocus placeholder="Username" autocapitalize="off" name="user" id="user"
				value="<?php echo filter_var(Input::old('user'), FILTER_SANITIZE_STRING); ?>"></p>

			<p><label for="pass"><?php echo __('users.password', 'Password'); ?>:</label>
			<input placeholder="Password" type="password" name="pass" id="pass"></p>

			<p class="buttons"><a href="<?php echo url('users/amnesia'); ?>">
				<?php echo __('users.forgotten_password', 'Forgotten your password?'); ?></a>
			<button type="submit"><?php echo __('users.login', 'Login'); ?></button></p>
		</fieldset>
	</form>

</section>

<script>
	(function() {
		var body = document.body;
		body.style.marginTop = (-(body.clientHeight / 2)) + 'px';
	}());
</script>

<?php echo $footer; ?>