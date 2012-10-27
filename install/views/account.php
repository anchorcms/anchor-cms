<?php echo $header; ?>

<section class="content">

	<article>
		<h1>Your first account</h1>

		<p>Oh, we're so tantalisingly close! All we need now is a username and password to log in to the admin area with. Just make sure you <a href="http://bash.org/?244321">pick a secure password</a>.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('account'); ?>" autocomplete="off">
		<fieldset>

			<?php echo $messages; ?>

			<p>
				<label>
					<strong>Username</strong>
					<span>C'mon, you know this.</span>
				</label>
				<input name="username" value="<?php echo Input::old('username', 'admin'); ?>">
			</p>

			<p>
				<label>
					<strong>Email address</strong>
					<span>If you forget your password.</span>
				</label>

				<input name="email" value="<?php echo Input::old('email'); ?>">
			</p>

			<p>
				<label>
					<strong>Password</strong>
					<span>Keep it safe, yo.</span>
				</label>
				<input name="password" type="password" value="<?php echo Input::old('password'); ?>">
			</p>
		</fieldset>

		<section class="options">
			<button type="submit">Complete</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php echo $footer; ?>