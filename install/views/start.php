<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Hello. Willkommen. Bonjour. Croeso.</h1>

		<p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve
		found the right place. Simply fill in the details below, and you&rsquo;ll have your
		new blog set up in no time.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('start'); ?>" autocomplete="off">
		<?php echo $messages; ?>

		<fieldset>
			<p>
				<label for="lang">
					<strong>Language</strong>
					<span class="info">Anchor's language.</span>
				</label>
				<select id="lang" name="language">
				<?php foreach($languages as $lang): ?>
					<option><?php echo $lang; ?></option>
				<?php endforeach; ?>
				</select>
			</p>
		</fieldset>

		<p>
			<small>You can get more languages by downloading them from <a href="//github.com/anchorcms/anchor-translations">the Anchor translations repository</a>, and placing in the <code>anchor/languages</code> folder.</small>
		</p>

		<section class="options">
			<button type="submit" class="btn">Next Step &raquo;</button>
		</section>
	</form>
</section>

<?php echo $footer; ?>