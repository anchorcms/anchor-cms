<?php echo $header; ?>

<section class="content">
	<article>
		<h1>Site metadata</h1>

		<p>In order to personalise your Anchor blog, it's recommended you add some metadata about your site. This can all be changed at any time, though.</p>
	</article>

	<form method="post" action="<?php echo Uri::make('metadata'); ?>" autocomplete="off">
		<fieldset>

			<?php echo $messages; ?>

			<p>
				<label>
					<strong>Site Name</strong>
					<span>Used in the <code>&lt;title&gt;</code>.</span>
				</label>

				<input name="site_name" value="<?php echo Input::old('site_name', 'My First Anchor Blog'); ?>">
			</p>

			<p>
				<label>
					<strong>Site Description</strong>
					<span>A short bio of the site.</span>
				</label>

				<textarea name="site_description"><?php echo Input::old('site_description',
					'It&rsquo;s not just any blog. It&rsquo;s an Anchor blog.'); ?></textarea>
			</p>

			<p>
				<label>
					<strong>Site Path</strong>
					<span>The path to Anchor.</span>
				</label>

				<input name="site_path" value="<?php echo Input::old('site_path', $path); ?>">
			</p>

			<p>
				<label>
					<strong>Theme</strong>
					<span>Your Anchor theme.</span>
				</label>
			<select name="theme">
				<option value="default">Default</option>
			</select></p>
		</fieldset>

		<section class="options">
			<button type="submit">Next Step &raquo;</button>
			<div class="test"></div>
		</section>
	</form>
</section>

<?php echo $footer; ?>