<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Your database details</h1>

		<p>Firstly, we’ll need a database. Anchor needs them to store all of your blog’s information, so it’s vital you fill these in correctly. If you don’t know what these are, you’ll need to contact your webhost.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('database'); ?>" autocomplete="off">

		<fieldset>
			<?php echo $messages; ?>

			<p>
			    <label for="host">Database Host</label>
    			<input id="host" name="host" value="<?php echo Input::old('host', '127.0.0.1'); ?>">
    		</p>

			<p>
			    <label for="port">Port</label>
    			<input id="port" name="port" value="<?php echo Input::old('port', '3306'); ?>">
    		</p>

			<p>
    			<label for="user">Username</label>
    			<input id="user" name="user" value="<?php echo Input::old('user', 'root'); ?>">
			</p>

			<p><label><strong>Password</strong>
			<input name="pass" value="<?php echo Input::old('pass'); ?>"></p>

			<p><label><strong>Database Name</strong>
			<input name="name" value="<?php echo Input::old('name', 'anchor'); ?>"></p>

			<p><label><strong>Database Collation</strong>
			<select name="collation">
				<?php foreach($collations as $code => $collation): ?>
				<option value="<?php echo $code; ?>" title="<?php echo $collation; ?>"<?php if($code == 'utf8_general_ci') echo ' selected'; ?>>
					<?php echo $code; ?>
				</option>
				<?php endforeach; ?>
			</select></p>
		</fieldset>

		<section class="options">
			<button type="submit">Next Step &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php echo $footer; ?>