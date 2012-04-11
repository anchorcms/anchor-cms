<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="assets/img/logo.png">
		</div>

		<ul>
			<li class="selected"><i class="icon-spanner"></i>Database Migration</li>
		</ul>

		<p>You're upgrading to Anchor <?php echo ANCHOR_VERSION; ?>. Hooray!</p>
	</nav>

	<article>
		<h1>Hello. Willkommen. Bonjour. Croeso.</h1>

		<p>This will patch your config file and migrate your database to the latest version, 
		please remeber to <strong>backup</strong> you existing database and files before your continue.</p>
	</article>

	<form method="post" action="index.php" autocomplete="off">
		<section class="options">
			<button type="submit">Start &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php render('layout/footer'); ?>