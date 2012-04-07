<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="assets/img/logo.png">
		</div>

		<ul>
			<li class="selected"><i class="icon-home"></i>Welcome</li>
			<li><i class="icon-spanner"></i>Database information</li>
			<li><i class="icon-pencil"></i>Site metadata</li>
			<li><i class="icon-user"></i>Your first account</li>
		</ul>

		<p>You're installing Anchor. Hooray!</p>
	</nav>

	<article>
		<h1>Welcome</h1>

		<p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve 
		found the right place. Simply fill in the details below, and you&rsquo;ll have your 
		new blog set up in no time.</p>
	</article>

	<form method="post" action="index.php" autocomplete="off">
		<fieldset>
			<p><label><strong>Language</strong><br>
			<span class="info">Anchor's language.</span></label>
			<select name="language">
				<option value="en_GB" selected>English</option>
			</select></p>
		</fieldset>

		<section class="options">
			<button type="submit">Next Step &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php render('layout/footer'); ?>