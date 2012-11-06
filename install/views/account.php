<?php echo $header; ?>

<section class="content">

	<article>
		<h1>Your first account</h1>

		<p>Oh, we're so tantalisingly close! All we need now is a username and password to log in to the admin area with.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('account'); ?>" autocomplete="off">
		<?php echo $messages; ?>

		<fieldset>
			<p>
				<label for="username">Username</label>
				<i>You use this to log in.</i>
				<input tabindex="1" id="username" name="username" value="<?php echo Input::old('username', 'admin'); ?>">
			</p>

			<p>
				<label for="email">Email address</label>
				<i>Needed if you can’t log in.</i>

				<input tabindex="2" id="email" type="email" name="email" value="<?php echo Input::old('email'); ?>">
			</p>

			<p>
				<label>Password</label>
				<i>Make sure to <a href="http://bash.org/?244321">pick a secure password</a>.</i>
				<input tabindex="3" name="password" type="password" value="<?php echo Input::old('password'); ?>">
			</p>
		</fieldset>

		<section class="options">
			<button type="submit" class="btn">Complete</button>
		</section>
	</form>
</section>

<?php echo $footer; ?>