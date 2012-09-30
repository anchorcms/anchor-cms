<form method="post" action="<?php echo Url::current(); ?>" enctype="multipart/form-data" novalidate>

	<header class="header">
		<div class="wrap">
    		<input autofocus autocomplete="off" tabindex="1" placeholder="Post title" id="title" name="title" value="<?php echo Input::post('title', $article->title); ?>">
    		
    		<p class="buttons">
    			<button tabindex="3" type="submit"><?php echo __('posts.save', 'Save'); ?></button>
    			<button class="red" name="delete" type="submit"><?php echo __('posts.delete', 'Delete'); ?></button>
    		</p>
    		
    		<?php echo Notifications::read(); ?>
    	</div>
	</header>
	
	<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">
	
	<div class="prevue">
	    <div class="wrap"></div>
	</div>

	<fieldset id="content">
		<p>
			<textarea tabindex="2" id="post-content" placeholder="<?php echo __('posts.content_explain', 'Just write.'); ?>" name="html"><?php echo Input::post('html', $article->html); ?></textarea>
		</p>
	</fieldset>
	<fieldset id="post-data">
		<div class="wrap">
			<!--<p>
				<label for="created"><?php echo __('posts.date', 'Date'); ?>:</label>
				<input type="date" id="created" autocomplete="off" name="created" value="<?php echo Input::post('created', date('jS M Y, h:i')); ?>">
				
				<em><?php echo __('posts.date_explain', 'The date your post will be published. Uses <code><a href="http://php.net/manual/en/function.strtotime.php">strtotime()</a></code>.'); ?></em>
			</p>-->

			<p>
				<label for="slug"><?php echo __('posts.slug', 'Slug'); ?>:</label>
				<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', $article->slug); ?>">
			</p>
			
			<p>
				<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
				<textarea id="description" name="description"><?php echo Input::post('description', $article->description); ?></textarea>
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
			</p>
			
			<p>
				<label for="category"><?php echo __('posts.category', 'Category'); ?>:</label>
				<select id="category" name="category">
					<?php foreach(Categories::list_all() as $cat): ?>
					<?php $selected = (Input::post('category', $article->category) == $cat->slug) ? ' selected' : ''; ?>
					<option value="<?php echo $cat->slug; ?>"<?php echo $selected; ?>>
						<?php echo $cat->title; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</p>
			
			<p>
				<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
				<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::post('comments', $article->comments)) echo ' checked'; ?>>
			</p>
			
			<div class="media-upload">
				<p>
					<label for="css"><?php echo __('posts.custom_css', 'Custom CSS'); ?>:</label>
					<textarea id="css" name="css"><?php echo Input::post('css'); ?></textarea>
				</p>
	
				<p>
					<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
					<textarea id="js" name="js"><?php echo Input::post('js'); ?></textarea>
				</p>
			</div>
		</div>
		

		<!--<legend><?php echo __('posts.custom_fields', 'Custom Fields'); ?></legend>
		<em><?php echo __('posts.custom_fields_explain', 'Create custom fields here.'); ?></em>

		<div id="fields">
			<?php foreach(Input::post('field', array()) as $data => $value): ?>
			<?php list($key, $label) = explode(':', $data); ?>
			<p>
				<label><?php echo $label; ?></label>
				<input name="field[<?php echo $key; ?>:<?php echo $label; ?>]" value="<?php echo $value; ?>">
			</p>
			<?php endforeach; ?>
		</div>

		<button id="create" type="button"><?php echo __('posts.create_custom_field', 'Create a custom field'); ?></button>-->
	</fieldset>
</form>