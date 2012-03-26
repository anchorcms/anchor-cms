<?php render('layout/header'); ?>

<div class="content">
	<h2>About your site</h2>

	<form method="post" action="index.php?action=stage3">

		<?php echo Messages::read(); ?>

		<fieldset>
			<p><label>Site Name<br>
			<input name="site_name" value="<?php echo post('site_name', 'My First Anchor Blog'); ?>"></label></p>

			<p><label>Site Description<br>
			<textarea name="site_description"><?php echo post('site_description', 'Welcome to my first Anchor blog'); ?></textarea></label></p>

			<p><label>Site Path<br>
			<input name="site_path" value="<?php echo post('site_path', dirname(dirname($_SERVER['REQUEST_URI']))); ?>"></label></p>

			<p><label>Theme<br>
			<select name="theme">
				<option value="default">Default</option>
			</select></label></p>
		</fieldset>

		<fieldset>
			<p><label>Username<br>
			<input name="username" value="<?php echo post('username', 'admin'); ?>"></label></p>

			<p><label>Email address<br>
			<input name="email" value="<?php echo post('email'); ?>"></label></p>

			<p><label>Password<br>
			<input name="password" type="password" value="<?php echo post('password'); ?>"></label></p>

			<p><label>Confirm Password<br>
			<input name="confirm_password" type="password" value="<?php echo post('confirm_password'); ?>"></label></p>
		</fieldset>

		<p><button type="submit">Continue</button></p>

	</form>
</div>

<?php render('layout/footer'); ?>