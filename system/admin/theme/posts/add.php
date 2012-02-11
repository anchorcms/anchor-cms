
<h1>Add a Post</h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<nav class="tabs">
		<ul>
			<li><a href="#post">Post</a></li>
			<li><a href="#customise">Customise</a></li>
			<li><a href="#fields">Custom Fields</a></li>
			<li><a href="#comments">Comments</a></li>
		</ul>
	</nav>
	<form method="post" action="<?php echo Url::current(); ?>" novalidate>
		<div data-tab="post" class="tab">

			<fieldset>
				<p>
	    			<label for="title">Title:</label>
	    			<input id="title" name="title" value="<?php echo Input::post('title'); ?>">
	    			
	    			<em>Your post&rsquo;s title.</em>
	    		</p>
				
				<p>
				    <label for="slug">Slug:</label>
				    <input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">
				    
				    <em>The slug for your post (<code id="output">slug</code>).</em>
				</p>
				
	            <p>
	                <label for="description">Description:</label>
	                <textarea id="description" name="description"><?php echo Input::post('description'); ?></textarea>
	                
	                <em>A brief outline of what your post is about. Used in the post introduction, RSS feed, and <code>&lt;meta name="description" /&gt;</code>.</em>
	            </p>
	            
				<p>
				    <label for="html">Content:</label>
				    <textarea id="html" name="html"><?php echo Input::post('html'); ?></textarea>
				    
				    <em>Your post's main content. Enjoys a healthy dose of valid HTML.</em>
				</p>
				
				<p>
				    <label>Status:</label>
	    			<select id="status" name="status">
	    				<?php foreach(array('draft', 'archived', 'published') as $status): ?>
	    				<?php $selected = (Input::post('status') == $status) ? ' selected' : ''; ?>
	    				<option value="<?php echo $status; ?>"<?php echo $selected; ?>>
	    					<?php echo ucwords($status); ?>
	    				</option>
	    				<?php endforeach; ?>
	    			</select>
	    			
	    			<em>Statuses: live (published), pending (draft), or hidden (archived).</em>
				</p>
				
				<p>
				    <label for="comments">Allow Comments:</label>
				    <input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::post('comments')) echo ' checked'; ?>>
				    <em>This will allow users to comment on your posts.</em>
				</p>
			</fieldset>
		
		</div>
		<div data-tab="customise" class="tab">

			<fieldset>
			    <legend>Customise</legend>
			    <em>Here, you can customise your posts. This section is optional.</em>
			    
			    <p>
			        <label for="css">Custom CSS:</label>
			        <textarea id="css" name="css"><?php echo Input::post('css'); ?></textarea>
			        
			        <em>Custom CSS. Will be wrapped in a <code>&lt;style&gt;</code> block.</em>
			    </p>

	            <p>
	                <label for="js">Custom JS:</label>
	                <textarea id="js" name="js"><?php echo Input::post('js'); ?></textarea>
	                
	                <em>Custom Javascript. Will be wrapped in a <code>&lt;script&gt;</code> block.</em>
	            </p>
			</fieldset>
		
		</div>
		<div data-tab="fields" class="tab">

			<fieldset>
			    <legend>Custom fields</legend>
			    <em>Create custom fields here.</em>

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
			</fieldset>

			<button id="create" type="button">Create a custom field</button>
		</div>
			
		<p class="buttons">
			<button type="submit">Create</button>
			<a href="<?php echo admin_url('posts'); ?>">Return to posts</a>
		</p>
	</form>

</section>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('assets/js/mootools.js'); ?>"><\/script>');</script>
<script src="<?php echo theme_url('assets/js/helpers.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/popup.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/custom_fields.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/tabs.js'); ?>"></script>
<script>
	(function() {
		var slug = $('slug'), output = $('output');

		// call the function to init the input text
		formatSlug(slug, output);

		// bind to input
		slug.addEvent('keyup', function() {formatSlug(slug, output)});
	}());
</script>