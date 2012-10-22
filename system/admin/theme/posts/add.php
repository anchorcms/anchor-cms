
<h1><?php echo __('posts.add_post', 'Add a Post'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<nav class="tabs">
		<ul>
			<li><a href="#post"><?php echo __('posts.post', 'Post'); ?></a></li>
			<li><a href="#customise"><?php echo __('posts.customise', 'Customise'); ?></a></li>
			<li><a href="#fields"><?php echo __('posts.custom_fields', 'Custom Fields'); ?></a></li>
		</ul>
	</nav>
	<form method="post" action="<?php echo Url::current(); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">

		<div data-tab="post" class="tab">

			<fieldset>
				<p>
					<label for="title"><?php echo __('posts.title', 'Title'); ?>:</label>
					<input id="title" name="title" value="<?php echo Input::post('title'); ?>">

					<em><?php echo __('posts.title_explain', 'Your post&rsquo;s title.'); ?></em>
				</p>

				<p>
					<label for="created"><?php echo __('posts.date', 'Date'); ?>:</label>
					<input type="text" id="created" autocomplete="off" name="created" value="<?php echo Input::post('created', date('jS M Y, h:i')); ?>">

					<em><?php echo __('posts.date_explain', 'The date your post will be published. Uses <code><a href="http://php.net/manual/en/function.strtotime.php">strtotime()</a></code>.'); ?></em>
				</p>

				<p>
					<label for="slug"><?php echo __('posts.slug', 'Slug'); ?>:</label>
					<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">

					<em><?php echo __('posts.slug_explain', 'The slug for your post.'); ?></em>
				</p>

				<p>
					<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
					<textarea id="description" name="description"><?php echo Html::encode(Input::post('description')); ?></textarea>

					<em><?php echo __('posts.description_explain', 'A brief outline of what your post is about.'); ?></em>
				</p>

				<p>
					<label for="html"><?php echo __('posts.content', 'Content'); ?>:</label>
					<textarea id="html" name="html"><?php echo Html::encode(Input::post('html')); ?></textarea>

					<em><?php echo __('posts.content_explain', 'Your post\'s main content. Enjoys a healthy dose of valid HTML.'); ?></em>
				</p>

				<p>
					<label><?php echo __('posts.status', 'Status'); ?>:</label>
					<select id="status" name="status">
						<?php foreach(array(
							'draft' => __('posts.draft', 'draft'),
							'archived' => __('posts.archived', 'archived'),
							'published' => __('posts.published', 'published')
						) as $value => $status): ?>
						<?php $selected = (Input::post('status') == $value) ? ' selected' : ''; ?>
						<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
							<?php echo $status; ?>
						</option>
						<?php endforeach; ?>
					</select>

					<em><?php echo __('posts.status_explain', 'Statuses: live (published), pending (draft), or hidden (archived).'); ?></em>
				</p>

				<p>
					<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
					<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::post('comments')) echo ' checked'; ?>>
					<em><?php echo __('posts.allow_comments_explain', 'This will allow users to comment on your posts.'); ?></em>
				</p>
			</fieldset>

		</div>
		<div data-tab="customise" class="tab">

			<fieldset>
				<legend><?php echo __('posts.customise', 'Customise'); ?></legend>
				<em><?php echo __('posts.customise_explain', 'Here, you can customise your posts. This section is optional.'); ?></em>

				<p>
					<label for="css"><?php echo __('posts.custom_css', 'Custom CSS'); ?>:</label>
					<textarea id="css" name="css"><?php echo Input::post('css'); ?></textarea>

					<em><?php echo __('posts.custom_css_explain', 'Custom CSS. Will be wrapped in a <code>&lt;style&gt;</code> block.'); ?></em>
				</p>

				<p>
					<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
					<textarea id="js" name="js"><?php echo Input::post('js'); ?></textarea>

					<em><?php echo __('posts.custom_js_explain', 'Custom Javascript. Will be wrapped in a <code>&lt;script&gt;</code> block.'); ?></em>
				</p>
			</fieldset>

		</div>
		<div data-tab="fields" class="tab">
			<fieldset>
				<legend><?php echo __('posts.custom_fields', 'Custom Fields'); ?></legend>
				<em><?php echo __('posts.custom_fields_explain', 'Create custom fields here.'); ?></em>

				<div id="fields">
					<!-- Re-Populate post data -->
					<?php foreach(Input::post('field', array()) as $data => $value): ?>
					<?php list($key, $label) = explode(':', $data); ?>
					<p>
						<label><?php echo $label; ?></label>
						<input name="field[<?php echo $key; ?>:<?php echo $label; ?>]" value="<?php echo $value; ?>">
					</p>
					<?php endforeach; ?>
				</div>

				<button id="create" type="button"><?php echo __('posts.create_custom_field', 'Create a custom field'); ?></button>
			</fieldset>
		</div>

		<p class="buttons">
			<button type="submit"><?php echo __('posts.create', 'Create'); ?></button>
			<a href="<?php echo admin_url('posts'); ?>"><?php echo __('posts.return_posts', 'Return to posts'); ?></a>
		</p>
	</form>

</section>

<script src="<?php echo theme_url('assets/js/lang.js'); ?>"></script>
<script>
	// define global js translations
	// for our popups
	Lang.load('posts');
</script>

<script src="<?php echo theme_url('assets/js/textareas.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/tabs.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/custom_fields.js'); ?>"></script>

