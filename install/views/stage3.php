<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>/assets/img/logo.png">
		</div>

		<ul>
			<li><i class="icon-spanner"></i>Database information</li>
			<li class="selected"><i class="icon-pencil"></i>Site metadata</li>
			<li><i class="icon-user"></i>Your first account</li>
		</ul>

		<p>You're installing Anchor <?php echo ANCHOR_VERSION; ?>. Hooray!</p>
	</nav>

	<article>
		<h1>Site metadata</h1>

		<p>In order to personalise your Anchor blog, it's recommended you add some metadata about your site. This can all be changed at any time, though.</p>
	</article>

	<form method="post" action="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>/index.php?action=stage3" autocomplete="off">
		<fieldset>

			<?php echo Messages::read(); ?>

			<p>
				<label>
					<strong>Site Name</strong>
					<span>Used in the <code>&lt;title&gt;</code>.</span>
				</label>

				<input name="site_name" value="<?php echo post('site_name', 'My First Anchor Blog'); ?>">
			</p>

			<p>
				<label>
					<strong>Site Description</strong>
					<span>A short bio of the site.</span>
				</label>

				<textarea name="site_description"><?php echo post('site_description', 'It&rsquo;s not just any blog. It&rsquo;s an Anchor blog.'); ?></textarea>
			</p>

			<p>
				<label>
					<strong>Site Path</strong>
					<span>The path to Anchor.</span>
				</label>

				<input name="site_path" value="<?php echo post('site_path', dirname(dirname($_SERVER['REQUEST_URI']))); ?>">
			</p>

			<p>
				<label>
					<strong>Theme</strong>
					<span>Your Anchor theme.</span>
				</label>
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