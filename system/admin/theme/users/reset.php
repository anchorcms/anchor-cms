<h1><?php echo __('users.reset_password', 'Reset Password'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" >
		<fieldset>
			<legend><?php echo __('users.reset_password_for', 'Password reset for '); ?> <?php echo $user->real_name; ?></legend>
			<em><?php echo __('users.reset_password_explain', 'Please enter a new password that you won&rsquo;t forget this time.'); ?></em>
			
			<p>
			    <label for="password"><?php echo __('users.password', 'Password'); ?>:</label>
			    <input name="password" id="password" type="password" value="<?php echo Input::post('password'); ?>">
			</p>

			<p class="buttons">
			    <button type="submit"><?php echo __('users.submit', 'Submit'); ?></button>
			    <a href="<?php echo Url::make(); ?>"><?php echo __('users.back_to', 'Back to '); ?> <?php echo Config::get('metadata.sitename'); ?></a>
			</p>
		</fieldset>
	</form>

</section>