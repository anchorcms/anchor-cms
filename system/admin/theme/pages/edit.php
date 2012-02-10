<h1>Editing &ldquo;<?php echo truncate($page->name, 4); ?>&rdquo;</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="name">Name:</label>
    			<input id="name" name="name" value="<?php echo Input::post('name', $page->name); ?>">
    			
    			<em>The name of your page. This gets shown in the navigation.</em>
    		</p>
			
			<p>
			    <label>Title:</label>
			    <input id="title" name="title" value="<?php echo Input::post('title', $page->title); ?>">
			    
			    <em>The title of your page, which gets shown in the <code>&lt;title&gt;</code>.</em>
			</p>
			
			<p>
			    <label for="slug">Slug:</label>
			    <input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', $page->slug); ?>">
			    
			    <em>The slug for your page (<code id="output">slug</code>).</em>
			</p>
			
			<p>
			    <label for="content">Content:</label>
			    <textarea id="content" name="content"><?php echo Input::post('content', $page->content); ?></textarea>
			    
			    <em>Your page's content. Accepts valid HTML.</em>
			</p>
			
			<p>
			    <label>Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('draft', 'archived', 'published') as $status): ?>
    				<?php $selected = (Input::post('status', $page->status) == $status) ? 'selected' : ''; ?>
    				<option value="<?php echo $status; ?>"<?php echo $selected; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Do you want your page to be live (published), pending (draft), or hidden (archived)?</em>
			</p>
		</fieldset>
			
		<p class="buttons">

			<button name="save" type="submit">Save</button>
			<?php 
			// Dont delete our posts page or home page
			if(in_array($page->id, array(Config::get('metadata.home_page'), Config::get('metadata.posts_page'))) === false): ?>
			<button name="delete" type="submit">Delete</button>
			<?php endif; ?>
			
			<a href="<?php echo admin_url('pages'); ?>">Return to pages</a>
		</p>
	</form>

</section>

<aside id="sidebar">
	<h2>Editing</h2>
	<em>Some useful links.</em>
	<ul>
		<li><a href="<?php echo Url::make($page->slug); ?>">View this page on your site</a></li>
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

