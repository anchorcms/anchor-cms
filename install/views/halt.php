<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Woops!</h1>

		<?php if(count($errors) > 1): ?>
		<ul>
			<?php foreach($errors as $error): ?>
			<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
		<?php else: ?>
		<p><?php echo current($errors); ?></p>
		<?php endif; ?>

		<p class="options">
			<a class="btn" href="<?php echo uri_to('start'); ?>">Let&apos;s try that again.</a>
		</p>
	</article>
</section>

<?php echo $footer; ?>