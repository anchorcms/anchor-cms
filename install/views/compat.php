<?php render('layout/header'); ?>

<section class="content small">
	<h1>Oh, no!</h1>

	<p>Unfortunately, it looks like this vessel can't handle Anchor. Here's the missing requirements:</p>

	<ul>
		<?php foreach($compat as $item): ?>
		<li><?php echo $item; ?></li>
		<?php endforeach; ?>
	</ul>

	<p><a class="button" href="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>/index.php">Continue</a></p>
</section>

<?php render('layout/footer'); ?>