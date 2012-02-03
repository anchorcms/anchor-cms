
<h1>Login</h1>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" >
		<fieldset>
			<legend>Log in</legend>
			
			<?php echo notifications(); ?>
			
			<p><label>Username<br>
			<input name="user" value="<?php echo post_user(); ?>"></label></p>
			
			<p><label>Password<br>
			<input name="pass" type="password"></label></p>

			<p><button type="submit">Login</button></p>
		</fieldset>
	</form>

</section>

