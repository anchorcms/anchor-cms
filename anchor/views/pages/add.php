<?php echo $header; ?>

<form method="post" action="<?php echo url('pages/add'); ?>" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<header class="header">
		<div class="wrap">
			<?php echo $messages; ?>

			<input autofocus autocomplete="off" tabindex="1" placeholder="Page name" id="name" name="name"
				value="<?php echo Input::old('name'); ?>">

			<p class="buttons">
				<button tabindex="3" class="btn" type="submit"><?php echo __('pages.create', 'Create'); ?></button>
				<button class="secondary btn">Redirect</button>
			</p>

			<p class="redirect">
				<input id="redirect_url" placeholder="<?php echo __('pages.redirect_url', 'Redirect Url'); ?>" name="redirect" value="<?php echo Input::old('redirect'); ?>">
			</p>
		</div>
	</header>

	<fieldset id="content">
		<p>
			<textarea id="post-content" placeholder="<?php echo __('pages.content_explain', 'Your page’s content. Uses Markdown.'); ?>" name="content"><?php echo Input::old('content'); ?></textarea>
		</p>
	</fieldset>

	<fieldset id="post-data" class="split">
		<div class="wrap">
			<p>
				<label><?php echo __('pages.title', 'Title'); ?>:</label>
				<input id="title" name="title" value="<?php echo Input::old('title'); ?>">

				<em><?php echo __('pages.title_explain',
					'The title of your page, which gets shown in the <code>&lt;title&gt;</code>.'); ?></em>
			</p>

			<p>
				<label for="slug"><?php echo __('pages.slug', 'Slug'); ?>:</label>
				<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::old('slug'); ?>">

				<em><?php echo __('pages.slug_explain',
					'The slug for your post (<code>/<span id="output">slug</span></code>).'); ?></em>
			</p>

			<p>
				<label for="content"><?php echo __('pages.content', 'Content'); ?>:</label>
				<textarea id="content" name="content"><?php echo Input::old('content'); ?></textarea>

				<em><?php echo __('pages.content_explain', 'Your page\'s content. Uses Markdown.'); ?></em>
			</p>

			<p>
				<label for="redirect"><?php echo __('pages.redirect_option',
					'This page triggers a redirect to another url'); ?>:</label>
				<?php $checked = Input::old('redirect') ? ' checked' : ''; ?>
				<input id="redirect" type="checkbox"<?php echo $checked; ?>>
			</p>

			<p>
				<label><?php echo __('pages.status', 'Status'); ?>:</label>
				<select id="status" name="status">
					<?php foreach(array(
						'published' => __('pages.published', 'Published'),
						'archived' => __('pages.archived', 'Archived'),
						'draft' => __('pages.draft', 'Draft')
					) as $value => $status): ?>
					<?php $selected = (Input::old('status') == $value) ? ' selected' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
						<?php echo $status; ?>
					</option>
					<?php endforeach; ?>
				</select>

				<em><?php echo __('pages.status_explain',
					'Do you want your page to be live (published), pending (draft), or hidden (archived)?'); ?></em>
			</p>

			<?php foreach($fields as $field): ?>
			<p>
				<label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>
			</p>
			<?php endforeach; ?>
		</div><!-- /.wrap -->
	</fieldset>
</form>

</section>

<?php echo $footer; ?>