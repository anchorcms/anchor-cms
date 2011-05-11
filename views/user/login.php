<div id="content">
	<div id="left">
		<h1>Log In</h1>
		
		<?php if(isset($error) === true) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
		
		<form action="" method="post">
			<p>
				<label for="username">Username:</label>
				<input id="username" name="username" value="<?php echo (isset($_POST['username']) === true) ? $_POST['username'] : '' ?>" />
			</p>
			<p>
				<label for="password">Password:</label>
				<input id="password" name="password" type="password" value="" />
			</p>
			<p>
				<label for="remember">Remember me?</label>
				<input id="remember" name="remember_me" type="checkbox"<?php echo (isset($_POST['remember_me']) === true) ? ' checked' : '' ?> />
			</p>
			<p>
				<input name="submit" type="submit" value="Log In" />
			</p>
		</form>
	</div>
</div>