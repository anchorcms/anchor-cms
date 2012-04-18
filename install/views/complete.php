<?php render('layout/header'); ?>

<section class="content small">

	<h1>Thanks for installing!</h1>

	<?php echo Messages::read(); ?>
	
	<p>You did a good job, private. I guess you'll want to try out Anchor, huh? Not a problem. Here's the links below for you to play with. Any problems, just <a href="//twitter.com/anchorcms">tweet us</a>.</p>

	<?php if(isset($_SESSION['config'])): ?>
	<p><a href="index.php?action=download">Download <code>config.php</code></a></p>
	<?php endif; ?>

	<p class="options">
	
		<a href="../admin" class="button">Admin &raquo;</a> 
	
		<a href="../" class="right">Check out your new site &raquo;</a>
	</p>
</section>

<?php render('layout/footer'); ?>