<h1>Log in</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" >
		<fieldset>
			
			<p>
			    <label for="user">Username:</label>
			    <input autocapitalize="off" name="user" id="user" value="<?php echo Input::post('user'); ?>">
			</p>
			
			<p>
    			<label for="pass">Password:</label>
    			<input type="password" name="pass" id="pass">
    			
    			<em>If you&rsquo;ve forgotten your password, contact the site admin.</em>
			</p>

			<p class="buttons">
			    <button type="submit">Login</button>
			    <a href="<?php echo Url::make('admin/users/amnesia'); ?>">Help, I've forgotten my password.</a><br>
			    <a href="<?php echo Url::make(); ?>">Back to <?php echo Config::get('metadata.sitename'); ?></a>
			</p>
		</fieldset>
	</form>

</section>

