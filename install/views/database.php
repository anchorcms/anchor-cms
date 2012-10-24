<?php echo $header; ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="<?php echo Html::asset('views/assets/img/logo.png'); ?>">
		</div>

		<ul>
			<li class="selected"><i class="icon-spanner"></i>Database information</li>
			<li><i class="icon-pencil"></i>Site metadata</li>
			<li><i class="icon-user"></i>Your first account</li>
		</ul>

		<p>You're installing Anchor 0.8. Hooray!</p>
	</nav>

	<article>
		<h1>Database information</h1>

		<p>To function correctly, Anchor requires a MySQL database (available
		with all good hosts). You'll need to know the credentials for said
		database to get any further. If you don't have these, try contacting your webhost.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('database'); ?>" autocomplete="off">

		<fieldset>
			<?php echo $messages; ?>

			<p><label><strong>Database Host</strong>
			<span class="info">Probably <code>localhost</code>.</span></label>
			<input name="host" value="<?php echo Input::old('host', 'localhost'); ?>"></p>

			<p><label><strong>Database Port</strong>
			<span class="info">Probably <code>3306</code>.</span></label>
			<input name="port" value="<?php echo Input::old('port', '3306'); ?>"></p>

			<p><label><strong>Username</strong>
			<span class="info">Self-explanatory.</span></label>
			<input name="user" value="<?php echo Input::old('user', 'root'); ?>"></p>

			<p><label><strong>Password</strong>
			<span class="info">Hangs out with "username".</span></label>
			<input name="pass" value="<?php echo Input::old('pass'); ?>"></p>

			<p><label><strong>Database Name</strong>
			<span class="info">Also self-explanatory.</span></label>
			<input name="name" value="<?php echo Input::old('name', 'anchor'); ?>"></p>

			<p><label><strong>Database Collation</strong>
			<span class="info">Character set for comparisons.</span></label>
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