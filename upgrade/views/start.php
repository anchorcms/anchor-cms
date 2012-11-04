<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Hello. Willkommen. Bonjour. Croeso.</h1>

		<p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve
		found the right place. Simply fill in the details below, and you&rsquo;ll have your
		new blog set up in no time.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('start'); ?>" autocomplete="off">
		<section class="options">
			<button type="submit">Begin &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php echo $footer; ?>