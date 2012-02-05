<h1>Editing &ldquo;<?php echo truncate(page_name(), 4); ?>&rdquo;</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="name">Name:</label>
    			<input id="name" name="name" value="<?php echo Input::post('name', page_name()); ?>">
    			
    			<em>The name of your page. This gets shown in the navigation.</em>
    		</p>
			
			<p>
			    <label>Title:</label>
			    <input id="title" name="title" value="<?php echo Input::post('title', page_title()); ?>">
			    
			    <em>The title of your page, which gets shown in the <code>&lt;title&gt;</code>.</em>
			</p>
			
			<p>
			    <label for="slug">Slug:</label>
			    <input type="url" id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', page_slug()); ?>">
			    
			    <em>The slug for your post (<code><?php echo $_SERVER['HTTP_HOST']; ?>/<span id="output">slug</span></code>).</em>
			</p>
			
			<p>
			    <label for="content">Content:</label>
			    <textarea id="content" name="content"><?php echo Input::post('content', page_content()); ?></textarea>
			    
			    <em>Your page's content. Accepts valid HTML.</em>
			</p>
			
			<p>
			    <label>Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('draft', 'archived', 'published') as $status): ?>
    				<option value="<?php echo $status; ?>"<?php if(Input::post('status', page_status()) == $status) echo 'selected'; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Do you want your page to be live (published), pending (draft), or hidden (archived)?</em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button name="save" type="submit">Save</button>
			<button name="delete" type="submit">Delete</button>
			<a href="<?php echo base_url('admin/pages'); ?>">Return to pages</a>
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


