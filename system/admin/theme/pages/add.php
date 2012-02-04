
<h1>Add a Page</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
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
			    <input type="url" id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">
			    
			    <em>The slug for your post (<code><?php echo $_SERVER['HTTP_HOST']; ?>/<span id="output">slug</span></code>).</em>
			    
			    <script>
		        var slug = document.getElementById('slug'),
		            output = document.getElementById('output'),
		            
		            fill = function(e) {
		                var me = (typeof e !== 'undefined' ? this : slug),
		                    val = me.value.replace(/[^0-9a-z\-]/ig, '');

		                output.innerText = val !== '' ? val : 'slug';
		            };
		            
		        fill();
		        
		        slug.onkeyup = fill;
		    </script>
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
			<a href="/admin/pages">Return to pages</a>
		</p>
	</form>

</section>

