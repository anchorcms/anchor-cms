
<h1>Add a Page</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="name">Name:</label>
    			<input id="name" name="name" value="<?php echo Input::post('name'); ?>">
    			
    			<em>The name of your page. This gets shown in the navigation.</em>
    		</p>
			
			<p>
			    <label>Title:</label>
			    <input id="title" name="title" value="<?php echo Input::post('title'); ?>">
			    
			    <em>The title of your page, which gets shown in the <code>&lt;title&gt;</code>.</em>
			</p>
			
			<p>
			    <label for="slug">Slug:</label>
			    <input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">
			    
			    <em>The slug for your post (<code><?php echo $_SERVER['HTTP_HOST']; ?>/<span id="output">slug</span></code>).</em>
			</p>
			
			<p>
			    <label for="content">Content:</label>
			    <textarea id="content" name="content"><?php echo Input::post('content'); ?></textarea>
			    
			    <em>Your page's content. Accepts valid HTML.</em>
			</p>
			
			<p>
			    <label>Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('draft', 'archived', 'published') as $status): ?>
    				<option value="<?php echo $status; ?>" <?php if(Input::post('status') == $status) echo 'selected'; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Do you want your page to be live (published), pending (draft), or hidden (archived)?</em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit">Create</button>
			<a href="<?php echo admin_url('pages'); ?>">Return to pages</a>
		</p>
	</form>

</section>

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


