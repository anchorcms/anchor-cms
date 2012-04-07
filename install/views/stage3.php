<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="assets/img/logo.png">
		</div>

		<ul>
			<li><i class="icon-home"></i>Welcome</li>
			<li><i class="icon-spanner"></i>Database information</li>
			<li class="selected"><i class="icon-pencil"></i>Site metadata</li>
			<li><i class="icon-user"></i>Your first account</li>
		</ul>

		<p>You're installing Anchor. Hooray!</p>
	</nav>

	<article>
		<h1>Site metadata</h1>

		<p>Some text</p>
	</article>

	<form method="post" action="index.php?action=stage3" autocomplete="off">
		<fieldset>

			<?php echo Messages::read(); ?>

			<p><label><strong>Site Name</strong></label>
			<input name="site_name" value="<?php echo post('site_name', 'My First Anchor Blog'); ?>"></p>

			<p><label><strong>Site Description</strong></label>
			<textarea name="site_description"><?php echo post('site_description', 'Welcome to my first Anchor blog'); ?></textarea></p>

			<p><label><strong>Site Path</strong></label>
			<input name="site_path" value="<?php echo post('site_path', dirname(dirname($_SERVER['REQUEST_URI']))); ?>"></p>

			<p><label><strong>Theme</strong></label>
			<select name="theme">
				<option value="default">Default</option>
			</select></p>
		</fieldset>

		<section class="options">
			<button type="submit">Next Step &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php render('layout/footer'); ?>