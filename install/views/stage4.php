<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="assets/img/logo.png">
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

		<p>Some text</p>
	</article>

	<form method="post" action="index.php?action=stage4" autocomplete="off">
		<fieldset>

			<?php echo Messages::read(); ?>

			<p><label><strong>Username</strong></label>
			<input name="username" value="<?php echo post('username', 'admin'); ?>"></p>

			<p><label><strong>Email address</strong></label>
			<input name="email" value="<?php echo post('email'); ?>"></p>

			<p><label><strong>Password</strong></label>
			<input name="password" type="password" value="<?php echo post('password'); ?>"></p>

			<p><label><strong>Confirm Password</strong></label>
			<input name="confirm_password" type="password" value="<?php echo post('confirm_password'); ?>"></p>
		</fieldset>

		<section class="options">
			<button type="submit">Complete</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php render('layout/footer'); ?>