<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Woops!</h1>

		<?php foreach($errors as $error): ?>
		<p><?php echo $error; ?></p>
		<?php endforeach; ?>

		<br>

		<p class="options">
			<a href="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>" class="button">Let&apos;s try that again.</a>
		</p>
	</article>
</section>

<?php echo $footer; ?>