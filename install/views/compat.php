<?php render('layout/header'); ?>

<div class="content">
	<h2>Woops</h2>
	
	<p>Looks like Anchor is missing some requirements:</p>
	
	<ul>
		<?php foreach($compat as $item): ?>
		<li><?php echo $item; ?></li>
		<?php endforeach; ?>
	</ul>
	
	<p><a href="./index.php">Continue</a></p>
</div>

<?php render('layout/footer'); ?>