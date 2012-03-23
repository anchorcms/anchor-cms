<?php render('layout/header'); ?>

<div class="content">
	<h2>Thanks for installing!</h2>

	<?php echo Messages::read(); ?>

	<?php if(isset($_SESSION['config'])): ?>
	<p><a href="index.php?action=download">Download <code>config.php</code></a></p>
	<?php endif; ?>

	<p><a href="../index.php">Continue</a></p>
</div>

<?php render('layout/footer'); ?>