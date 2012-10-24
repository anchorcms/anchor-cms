<?php echo $header; ?>

<section class="content">
	<nav>
		<div class="logo">
			<img src="<?php echo Html::asset('views/assets/img/logo.png'); ?>">
		</div>

		<ul>
			<li><i class="icon-spanner"></i>Database information</li>
			<li><i class="icon-pencil"></i>Site metadata</li>
			<li><i class="icon-user"></i>Your first account</li>
		</ul>

		<p>You're installing Anchor 0.8. Hooray!</p>
	</nav>

	<article>
		<h1>Hello. Willkommen. Bonjour. Croeso.</h1>

		<p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve
		found the right place. Simply fill in the details below, and you&rsquo;ll have your
		new blog set up in no time.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('start'); ?>" autocomplete="off">
		<fieldset>
			<?php echo $messages; ?>

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
			<button type="submit">Next Step &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php echo $footer; ?>