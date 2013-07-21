<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Woops!</h1>

		<?php if(count($errors)): ?>
		<ul>
			<?php foreach($errors as $error): ?>
			<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>

		<p class="options">
			<a class="btn" href="<?php echo uri_to('start'); ?>">Let&apos;s try that again.</a>
		</p>
		<?php else: ?>
		<p>Everything looks good, let's get started.</p>

		<p class="options">
			<a class="btn" href="<?php echo uri_to('start'); ?>">Let&apos;s try that again.</a>
		</p>
		<?php endif; ?>
	</article>
</section>

<?php echo $footer; ?>