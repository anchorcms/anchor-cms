<h1><?php echo __('users.log_in', 'Log in'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>">

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">
		
		<fieldset>
			
			<p>
			    <label for="user"><?php echo __('users.username', 'Username'); ?>:</label>
			    <input autocapitalize="off" name="user" id="user" value="<?php echo filter_var(Input::post('user'), FILTER_SANITIZE_STRING); ?>">
			</p>
			
			<p>
    			<label for="pass"><?php echo __('users.password', 'Password'); ?>:</label>
    			<input type="password" name="pass" id="pass">
    			
    			<em><a href="<?php echo admin_url('users/amnesia'); ?>"><?php echo __('users.forgotten_password', 'Forgotten your password?'); ?></a></em>
			</p>

			<p class="buttons">
			    <button type="submit"><?php echo __('users.login', 'Login'); ?></button>
			    <a href="<?php echo Url::make(); ?>">Back to <?php echo Config::get('metadata.sitename'); ?></a>
			</p>
		</fieldset>
	</form>

</section>