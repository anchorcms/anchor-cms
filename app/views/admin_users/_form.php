<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
	
	<form action="" method="post" enctype="multipart/form-data">
		<p>
			<label for="username">Username:</label>
			<input id="username" name="user[username]" value="<?php echo isset($user->username) ? $user->username : ''; ?>" />
		</p>
		<p>
			<label for="userpassword">Password:</label>
			<input id="userpassword" name="user[password]" value="" />
		</p>
		<p>
			<input name="submit" type="submit" value="Save changes" />
		</p>
	</form>