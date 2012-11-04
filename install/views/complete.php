<?php echo $header; ?>

<section class="content small">
	<h1>Anchor installation complete!</h1>

	<p class="options">
		<a href="<?php echo rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/'; ?>index.php/admin" class="button">Visit your admin panel</a>
		<a href="<?php echo rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/'); ?>" class="right">Visit your new site</a>
	</p>
</section>

<?php echo $footer; ?>