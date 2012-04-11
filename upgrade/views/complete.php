<?php render('layout/header'); ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="assets/img/logo.png">
		</div>

		<ul>
			<li><i class="icon-spanner"></i>Database Migration</li>
		</ul>

		<p>You've upgraded to Anchor <?php echo ANCHOR_VERSION; ?>. Hooray!</p>
	</nav>

	<article>
		<h1>Thanks for upgrading!</h1>

		<p>Your database and config file has been updated.</p>
	</article>

	<form method="get" action="../index.php" autocomplete="off">
		<section class="options">
			<button type="submit">Continue &raquo;</button>
		</section>
	</form>
</section>

<?php render('layout/footer'); ?>