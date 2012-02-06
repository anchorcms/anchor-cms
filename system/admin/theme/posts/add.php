
<h1>Add a Post</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="title">Title:</label>
    			<input id="title" name="title" value="<?php echo Input::post('title'); ?>">
    			
    			<em>Your post&rsquo;s title.</em>
    		</p>
			
			<p>
			    <label for="slug">Slug:</label>
			    <input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">
			    
			    <em>The slug for your post (<code><?php echo $_SERVER['HTTP_HOST']; ?>/posts/<span id="output">slug</span></code>).</em>
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
    				<option value="<?php echo $status; ?>" <?php if(Input::post('status') == $status) echo 'selected'; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Statuses: live (published), pending (draft), or hidden (archived).</em>
			</p>
		</fieldset>
		
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
            
            <p>
                <label for="key1">Custom fields:</label>
                <input class="key" id="key1" name="key1" placeholder="Key">
                <input class="value" id="value1" name="value1" placeholder="Value">
                
                <label></label>
                <input class="key" id="key2" name="key2" placeholder="Key">
                <input class="value" id="value2" name="value2" placeholder="Value">
                
                <label></label>
                <input class="key" id="key3" name="key3" placeholder="Key">
                <input class="value" id="value3" name="value3" placeholder="Value">

                <em>Custom key-value pairs of arbitrary data that can be used in a theme.</em>
            </p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit">Create</button>
			<a href="<?php echo base_url('admin/posts'); ?>">Return to posts</a>
		</p>
	</form>

</section>

<script>
	(function() {
		var slug = document.getElementById('slug'), output = document.getElementById('output');

		// call the function to init the input text
		formatSlug(slug, output);

		// bind to input
		addEvent(slug, 'keyup', function() {formatSlug(slug, output)});
	}());
</script>

