<h1>Reset Password</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" >
		<fieldset>
			<legend>Password reset for <?php echo user_real_name(); ?></legend>
			<em>Please enter a new password that you wont forget this time.</em>
			
			<p>
			    <label for="password">Password:</label>
			    <input name="password" id="password" type="password" value="<?php echo Input::post('password'); ?>">
			</p>

			<p class="buttons">
			    <button type="submit">Submit</button>
			    <a href="<?php echo base_url(); ?>">Back to <?php echo site_name(); ?></a>
			</p>
		</fieldset>
	</form>

</section>

