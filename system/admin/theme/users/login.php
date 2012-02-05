<h1>Log in</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" >
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
			    
			    <a href="<?php echo base_url(); ?>">Back to <?php echo site_name(); ?></a>
			</p>
		</fieldset>
	</form>

</section>

