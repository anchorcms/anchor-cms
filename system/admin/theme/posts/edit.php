
<h1>Editing &ldquo;<?php echo truncate($article->title, 4); ?>&rdquo;</h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<nav class="tabs">
		<ul>
			<li><a href="#post"><?php echo __('posts.post', 'Post'); ?></a></li>
			<li><a href="#customise"><?php echo __('posts.customise', 'Customise'); ?></a></li>
			<li><a href="#fields"><?php echo __('posts.custom_fields', 'Custom Fields'); ?></a></li>
			<li>
				<a href="#comments"><?php echo __('posts.comments', 'Comments'); ?>
				<?php if($pending > 0): ?>
					<span title="You have <?php echo $pending; ?> comments"><?php echo $pending; ?></span>
				<?php endif; ?>
				</a>
			</li>
		</ul>
	</nav>
	<form method="post" action="<?php echo Url::current(); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">

		<div data-tab="post" class="tab">

			<fieldset>
				<p>
					<label for="title"><?php echo __('posts.title', 'Title'); ?>:</label>
					<input id="title" name="title" value="<?php echo Input::post('title', $article->title); ?>">

					<em><?php echo __('posts.title_explain', 'Your post&rsquo;s title.'); ?></em>
				</p>

				<p>
					<label for="created"><?php echo __('posts.date', 'Date'); ?>:</label>
					<input type="text" id="created" autocomplete="off" name="created" value="<?php echo Input::post('created', date('jS M Y, h:i', $article->created)); ?>">

					<em><?php echo __('posts.date_explain', 'The date your post will be published. Uses <code><a href="http://php.net/manual/en/function.strtotime.php">strtotime()</a></code>.'); ?></em>
				</p>

				<p>
					<label for="slug"><?php echo __('posts.slug', 'Slug'); ?>:</label>
					<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', $article->slug); ?>">

					<em><?php echo __('posts.slug_explain', 'The slug for your post.'); ?></em>
				</p>

				<p>
					<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
					<textarea id="description" name="description"><?php echo Html::encode(Input::post('description', $article->description)); ?></textarea>

					<em><?php echo __('posts.description_explain', 'A brief outline of what your post is about.'); ?></em>
				</p>

				<p>
					<label for="html"><?php echo __('posts.content', 'Content'); ?>:</label>
					<textarea id="html" name="html"><?php echo Html::encode(Input::post('html', $article->html)); ?></textarea>

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
						<?php $selected = (Input::post('status', $article->status) == $value) ? ' selected' : ''; ?>
						<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
							<?php echo $status; ?>
						</option>
						<?php endforeach; ?>
					</select>

					<em><?php echo __('posts.status_explain', 'Statuses: live (published), pending (draft), or hidden (archived).'); ?></em>
				</p>

				<p>
					<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
					<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::post('comments', $article->comments)) echo ' checked'; ?>>
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
					<textarea id="css" name="css"><?php echo Input::post('css', $article->css); ?></textarea>

					<em><?php echo __('posts.custom_css_explain', 'Custom CSS. Will be wrapped in a <code>&lt;style&gt;</code> block.'); ?></em>
				</p>

				<p>
					<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
					<textarea id="js" name="js"><?php echo Input::post('js', $article->js); ?></textarea>

					<em><?php echo __('posts.custom_js_explain', 'Custom Javascript. Will be wrapped in a <code>&lt;script&gt;</code> block.'); ?></em>
				</p>
			</fieldset>

		</div>
		<div data-tab="fields" class="tab">

			<fieldset>
				<legend><?php echo __('posts.custom_fields', 'Custom Fields'); ?></legend>
				<em><?php echo __('posts.custom_fields_explain', 'Create custom fields here.'); ?></em>

				<div id="fields">
					<!-- Re-Populate data -->
					<?php foreach(parse_fields($article->custom_fields) as $key => $data): ?>
					<p>
						<label><?php echo $data['label']; ?></label>
						<input name="field[<?php echo $key; ?>:<?php echo $data['label']; ?>]" value="<?php echo $data['value']; ?>">
					</p>
					<?php endforeach; ?>

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
		<div data-tab="comments" class="tab">

			<fieldset>
				<legend><?php echo __('posts.comments', 'Comments'); ?></legend>
				<em><?php echo __('posts.comments_explain', 'Here, you can moderate your comments.'); ?></em>

				<?php if(count($comments)): ?>
				<ul id="comments">
				<?php foreach($comments as $comment):?>
				<li id="c<?php echo $comment->id; ?>">
					<header>
						<p><strong><?php echo $comment->name; ?></strong>
						<?php echo date(Config::get('metadata.date_format'), $comment->date); ?><br>
						<?php $statuses = array(
							'pending' => __('posts.pending', 'Pending'),
							'published' => __('posts.published', 'Published'),
							'spam' => __('posts.spam', 'Spam')
						); ?>
						<em>
							<?php echo __('posts.status', 'Status'); ?>:
							<span class="status">
								<?php echo $statuses[$comment->status]; ?>
							</span>
						</em></p>
					</header>

					<p class="comment"><?php echo $comment->text; ?></p>

					<ul class="options">
						<?php if($comment->status == 'pending'): ?>
						<li><a href="#publish"><?php echo __('posts.publish', 'Publish'); ?></a></li>
						<?php endif; ?>
						<li><a href="#edit"><?php echo __('posts.edit', 'Edit'); ?></a></li>
						<li><a href="#delete"><?php echo __('posts.delete', 'Delete'); ?></a></li>
					</ul>
				</li>
				<?php endforeach; ?>
				</ul>
				<?php else: ?>
				<p><?php echo __('posts.no_comments', 'No comments yet.'); ?></p>
				<?php endif; ?>
			</fieldset>
		</div>

		<p class="buttons">
			<button name="save" type="submit"><?php echo __('posts.save', 'Save'); ?></button>
			<button name="delete" type="submit"><?php echo __('posts.delete', 'Delete'); ?></button>
			<a href="<?php echo admin_url('posts'); ?>"><?php echo __('posts.return_posts', 'Return to posts'); ?></a>
		</p>

	</form>
</section>

<aside id="sidebar">
	<h2><?php echo __('posts.editing', 'Editing'); ?></h2>
	<em><?php echo __('posts.editing_explain', 'Some useful links.'); ?></em>
	<ul>
		<li><a href="<?php echo Url::make($page->slug . '/' . $article->slug); ?>"><?php echo __('posts.view_post', 'View this post on your site'); ?></a></li>
	</ul>
</aside>

<script src="<?php echo theme_url('assets/js/lang.js'); ?>"></script>
<script>
	// define global js translations
	// for our popups
	Lang.load('posts');
</script>

<script src="<?php echo theme_url('assets/js/tabs.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/textareas.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/custom_fields.js'); ?>"></script>

<script src="<?php echo theme_url('assets/js/comments.js'); ?>"></script>
<script>
	Comments.init();
</script>

<script src="<?php echo theme_url('assets/js/confirm.js'); ?>"></script>
<script>
	// confirm for deletions
	$('button[name=delete]').bind('click', function(event) {
		Confirm.open(function() {
			var form = $('form'), input = new Element('input', {'type': 'hidden', 'name': 'delete'});
			form.append(input);
			form.submit();
		});
		event.end();
	});
</script>