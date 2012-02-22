
<h1><?php echo __('pages.editing', 'Editing'); ?> &ldquo;<?php echo truncate($page->name, 4); ?>&rdquo;</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate>
		<fieldset>
			<p>
				<label for="name"><?php echo __('pages.name', 'Name'); ?>:</label>
				<input id="name" name="name" value="<?php echo Input::post('name', $page->name); ?>">
				
				<em><?php echo __('pages.name_explain', 'The name of your page. This gets shown in the navigation.'); ?></em>
			</p>
			
			<p>
				<label><?php echo __('pages.title', 'Title'); ?>:</label>
				<input id="title" name="title" value="<?php echo Input::post('title', $page->title); ?>">
				
				<em><?php echo __('pages.title_explain', 'The title of your page, which gets shown in the <code>&lt;title&gt;</code>.'); ?></em>
			</p>
			
			<p>
				<label for="slug"><?php echo __('pages.slug', 'Slug'); ?>:</label>
				<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', $page->slug); ?>">
				
				<em><?php echo __('pages.slug_explain', 'The slug for your post (<code>/<span id="output">slug</span></code>).'); ?></em>
			</p>
			
			<p>
				<label for="content"><?php echo __('pages.content', 'Content'); ?>:</label>
				<textarea id="content" name="content"><?php echo Input::post('content', $page->content); ?></textarea>
				
				<em><?php echo __('pages.content_explain', 'Your page\'s content. Accepts valid HTML.'); ?></em>
			</p>
			
			<p>
				<label><?php echo __('pages.status', 'Status'); ?>:</label>
				<select id="status" name="status">
					<?php foreach(array(
						'draft' => __('pages.draft', 'Draft'), 
						'archived' => __('pages.archived', 'Archived'), 
						'published' => __('pages.published', 'Published')
					) as $value => $status): ?>
					<?php $selected = (Input::post('status', $page->status) == $value) ? ' selected' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
						<?php echo $status; ?>
					</option>
					<?php endforeach; ?>
				</select>
				
				<em><?php echo __('pages.status_explain', 'Do you want your page to be live (published), pending (draft), or hidden (archived)?'); ?></em>
			</p>
		</fieldset>
			
		<p class="buttons">

			<button name="save" type="submit"><?php echo __('pages.save', 'Save'); ?></button>
			<?php 
			// Dont delete our posts page or home page
			if(in_array($page->id, array(Config::get('metadata.home_page'), Config::get('metadata.posts_page'))) === false): ?>
			<button name="delete" type="submit"><?php echo __('pages.delete', 'Delete'); ?></button>
			<?php endif; ?>
			
			<a href="<?php echo admin_url('pages'); ?>"><?php echo __('pages.return_pages', 'Return to pages'); ?></a>
		</p>
	</form>

</section>

<aside id="sidebar">
	<h2><?php echo __('pages.editing', 'Editing'); ?></h2>
	<em><?php echo __('pages.editing_explain', 'Some useful links.'); ?></em>
	<ul>
		<li><a href="<?php echo Url::make($page->slug); ?>"><?php echo __('pages.view_page', 'View this page on your site'); ?></a></li>
	</ul>
</aside>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('assets/js/mootools.js'); ?>"><\/script>');</script>
<script src="<?php echo theme_url('assets/js/helpers.js'); ?>"></script>
<script>
	(function() {
		var slug = $('slug'), output = $('output');

		// call the function to init the input text
		formatSlug(slug, output);

		// bind to input
		slug.addEvent('keyup', function() {formatSlug(slug, output)});
	}());
</script>

