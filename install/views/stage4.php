<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>/assets/img/logo.png">
		</div>

		<ul>
			<li><i class="icon-spanner"></i>Database information</li>
			<li><i class="icon-pencil"></i>Site metadata</li>
			<li class="selected"><i class="icon-user"></i>Your first account</li>
		</ul>

		<p>You're installing Anchor <?php echo ANCHOR_VERSION; ?>. Hooray!</p>
	</nav>

	<article>
		<h1>Your first account</h1>

		<p>Oh, we're so tantalisingly close! All we need now is a username and password to log in to the admin area with. Just make sure you <a href="http://bash.org/?244321">pick a secure password</a>.</p>
	</article>

	<form method="post" action="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>/index.php?action=stage4" autocomplete="off">
		<fieldset>

			<?php echo Messages::read(); ?>

			<p>
				<label>
					<strong>Username</strong>
					<span>C'mon, you know this.</span>
				</label>
				<input name="username" value="<?php echo post('username', 'admin'); ?>">
			</p>

			<p>
				<label>
					<strong>Email address</strong>
					<span>If you forget your password.</span>
				</label>

				<input name="email" value="<?php echo post('email'); ?>">
			</p>

			<p>
				<label>
					<strong>Password</strong>
					<span>Keep it safe, yo.</span>
				</label>
				<input name="password" type="password" value="<?php echo post('password'); ?>">
			</p>
		</fieldset>

		<section class="options">
			<button type="submit">Complete</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php render('layout/footer'); ?>