
<h1><?php echo __('categories.add_category', 'Add a Category'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">
		
		<fieldset>
			<p>
				<label for="title"><?php echo __('categories.title', 'Title'); ?>:</label>
				<input id="title" name="title" value="<?php echo Input::post('title', $category->title); ?>">
				
				<em><?php echo __('categories.title_explain', 'Your category&rsquo;s title. Publicly shown.'); ?></em>
			</p>
			
			<p>
				<label for="slug"><?php echo __('categories.slug', 'Slug'); ?>:</label>
				<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', $category->slug); ?>">
				
				<em><?php echo __('categories.slug_explain', 'Your category&rsquo;s URL component (<code>/categories/<span id="output">slug</span></code>).'); ?></em>
			</p>
			
			<p>
				<label for="description"><?php echo __('categories.description', 'Description'); ?>:</label>
				<textarea id="description" name="description"><?php echo Input::post('description', $category->description); ?></textarea>
				
				<em><?php echo __('categories.description_explain', 'Your category&rsquo;s description. Accepts valid HTML.'); ?></em>
			</p>

			<p>
				<label for="visible"><?php echo __('categories.visible', 'Visible'); ?>:</label>
				<?php $checked = Input::post('visible', $category->visible) ? ' checked' : ''; ?>
				<input id="visible" name="visible" type="checkbox"<?php echo $checked; ?>>
				
				<em><?php echo __('categories.visible_explain', 'Should this category be publicly shown or not?'); ?></em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit"><?php echo __('categories.create', 'Create'); ?></button>
			<a href="<?php echo admin_url('categories'); ?>"><?php echo __('categories.return_categories', 'Return to categories'); ?></a>
		</p>
	</form>

</section>

<script src="<?php echo theme_url('assets/js/lang.js'); ?>"></script>
<script>
	// define global js translations
	// for our popups
	Lang.load('categories');
</script>

<script src="<?php echo theme_url('assets/js/textareas.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/redirect.js'); ?>"></script>
