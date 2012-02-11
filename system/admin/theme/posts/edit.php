
<h1>Editing &ldquo;<?php echo truncate($article->title, 4); ?>&rdquo;</h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<nav class="tabs">
		<ul>
			<li><a href="#post">Post</a></li>
			<li><a href="#customise">Customise</a></li>
			<li><a href="#fields">Custom Fields</a></li>
			<li>
			    <a href="#comments">Comments			    
			    <?php if($pending > 0): ?>
			        <span title="You have <?php echo $pending; ?> comments"><?php echo $pending; ?></span>
			    <?php endif; ?>
	            </a>
	        </li>
		</ul>
	</nav>
	<form method="post" action="<?php echo Url::current(); ?>" novalidate>

		<div data-tab="post" class="tab">

			<fieldset>
				<p>
	    			<label for="title">Title:</label>
	    			<input id="title" name="title" value="<?php echo Input::post('title', $article->title); ?>">
	    			
	    			<em>Your post&rsquo;s title.</em>
	    		</p>
				
				<p>
				    <label for="slug">Slug:</label>
				    <input type="url" id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', $article->slug); ?>">
				    
				    <em>The slug for your post (<code id="output">slug</code>).</em>
				</p>
				
	            <p>
	                <label for="description">Description:</label>
	                <textarea id="description" name="description"><?php echo Input::post('description', $article->description); ?></textarea>
	                
	                <em>A brief outline of what your post is about. Used in the post introduction, RSS feed, and <code>&lt;meta name="description" /&gt;</code>.</em>
	            </p>
	            
				<p>
				    <label for="html">Content:</label>
				    <textarea id="html" name="html"><?php echo Input::post('html', $article->html); ?></textarea>
				    
				    <em>Your post's main content. Enjoys a healthy dose of valid HTML.</em>
				</p>
				
				<p>
				    <label>Status:</label>
	    			<select id="status" name="status">
	    				<?php foreach(array('draft', 'archived', 'published') as $status): ?>
	    				<?php $selected = (Input::post('status', $article->status) == $status) ? ' selected' : ''; ?>
	    				<option value="<?php echo $status; ?>"<?php echo $selected; ?>>
	    					<?php echo ucwords($status); ?>
	    				</option>
	    				<?php endforeach; ?>
	    			</select>
	    			
	    			<em>Statuses: live (published), pending (draft), or hidden (archived).</em>
				</p>
				
				<p>
				    <label for="comments">Allow Comments:</label>
				    <input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::post('comments', $article->comments)) echo ' checked'; ?>>
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
			        <textarea id="css" name="css"><?php echo Input::post('css', $article->css); ?></textarea>
			        
			        <em>Custom CSS. Will be wrapped in a <code>&lt;style&gt;</code> block.</em>
			    </p>

	            <p>
	                <label for="js">Custom JS:</label>
	                <textarea id="js" name="js"><?php echo Input::post('js', $article->js); ?></textarea>
	                
	                <em>Custom Javascript. Will be wrapped in a <code>&lt;script&gt;</code> block.</em>
	            </p>
			</fieldset>
		
		</div>
		<div data-tab="fields" class="tab">

			<fieldset>
			    <legend>Custom fields</legend>
			    <em>Create custom fields here.</em>

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
				
				
				<button id="create" type="button">Create a custom field</button>
			</fieldset>
		
		</div>
		<div data-tab="comments" class="tab">

			<fieldset>
			    <legend>Comments</legend>
			    <em>Here, you can moderate your comments.</em>

			    <?php if(count($comments)): ?>
			    <ul id="comments">
			    <?php foreach($comments as $comment):?>
			    <li data-id="<?php echo $comment->id; ?>">
			    	<header>
    			    	<p><strong><?php echo $comment->name; ?></strong> 
    			    	<?php echo date(Config::get('metadata.date_format'), $comment->date); ?><br>
    			    	<em>Status: <span data-status="<?php echo $comment->id; ?>"><?php echo $comment->status; ?></span></em></p>
    			    </header>
    			    
			    	<p class="comment" data-text="<?php echo $comment->id; ?>"><?php echo $comment->text; ?></p>
			    	
			    	<ul class="options">
			    		<?php if($comment->status == 'pending'): ?>
			    		<li><a href="#publish">Publish</a></li>
			    		<?php endif; ?>
			    		<li><a href="#edit">Edit</a></li>
			    		<li><a href="#delete">Delete</a></li>
		    		</ul>
			    </li>
			    <?php endforeach; ?>
			    </ul>
			    <?php else: ?>
			    <p>No comments yet.</p>
			    <?php endif; ?>
			</fieldset>
		
		</div>

		<p class="buttons">
			<button name="save" type="submit">Save</button>
			<button name="delete" type="submit">Delete</button>
			<a href="<?php echo admin_url('posts'); ?>">Return to posts</a>
		</p>
		
	</form>
</section>

<aside id="sidebar">
	<h2>Editing</h2>
	<em>Some useful links.</em>
	<ul>
		<li><a href="<?php echo Url::make($page->slug . '/' . $article->slug); ?>">View this post on your site</a></li>
	</ul>
</aside>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('assets/js/mootools.js'); ?>"><\/script>');</script>
<script src="<?php echo theme_url('assets/js/helpers.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/popup.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/custom_fields.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/comments.js'); ?>"></script>
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