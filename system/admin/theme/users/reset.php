<h1>Reset Password</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" >
		<fieldset>
			<legend>Password reset for <?php echo $user->real_name; ?></legend>
			<em>Please enter a new password that you won&rsquo;t forget this time.</em>
			
			<p>
			    <label for="password">Password:</label>
			    <input name="password" id="password" type="password" value="<?php echo Input::post('password'); ?>">
			</p>

			<p class="buttons">
			    <button type="submit">Submit</button>
			    <a href="<?php echo Url::make(); ?>">Back to <?php echo Config::get('metadata.sitename'); ?></a>
			</p>
		</fieldset>
	</form>

</section>