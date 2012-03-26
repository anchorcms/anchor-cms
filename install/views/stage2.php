<?php render('layout/header'); ?>

<div class="content">
	<h2>Setting up your database</h2>

	<p>Make sure you have already created the database and setup a user to access it.</p>

	<form method="post" action="index.php?action=stage2">

		<?php echo Messages::read(); ?>

		<fieldset>
			<p><label>Host<br>
			<input name="host" value="<?php echo post('host', 'localhost'); ?>"></label></p>

			<p><label>Port<br>
			<input name="port" value="<?php echo post('port', '3306'); ?>"></label></p>

			<p><label>Username<br>
			<input name="user" value="<?php echo post('user', 'anchor'); ?>"></label></p>

			<p><label>Password<br>
			<input name="pass" value="<?php echo post('pass'); ?>"></label></p>

			<p><label>Database Name<br>
			<input name="name" value="<?php echo post('name', 'anchorcms'); ?>"></label></p>
		</fieldset>

		<p><button type="submit">Continue</button></p>

	</form>
</div>

<?php render('layout/footer'); ?>