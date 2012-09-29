<?php echo Notifications::read(); ?>

<form method="post" action="<?php echo Url::current(); ?>" enctype="multipart/form-data" novalidate>

	<header class="header">
		<div class="wrap">
    		<input autofocus autocomplete="off" tabindex="1" placeholder="Post title" id="title" name="title" value="<?php echo Input::post('title'); ?>">
    		
    		<p class="buttons">
    			<button tabindex="3" type="submit"><?php echo __('posts.create', 'Create'); ?></button>
    		</p>
    	</div>
	</header>
	
	<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">

	<div class="tabs">
		<div class="carousel">
			<fieldset id="content">
				<p>
					<textarea tabindex="2" id="post-content" placeholder="<?php echo __('posts.content_explain', 'Your post\'s main content. Enjoys a healthy dose of valid HTML or Markdown.'); ?>" name="html"><?php echo Input::post('html'); ?></textarea>
				</p>
			</fieldset>
			<fieldset id="post-data">
				<div class="side">
					<!--<p>
						<label for="created"><?php echo __('posts.date', 'Date'); ?>:</label>
						<input type="date" id="created" autocomplete="off" name="created" value="<?php echo Input::post('created', date('jS M Y, h:i')); ?>">
						
						<em><?php echo __('posts.date_explain', 'The date your post will be published. Uses <code><a href="http://php.net/manual/en/function.strtotime.php">strtotime()</a></code>.'); ?></em>
					</p>-->
	
					<p>
						<label for="slug"><?php echo __('posts.slug', 'Slug'); ?>:</label>
						<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">
						
						<em><?php echo __('posts.slug_explain', 'The slug for your post.'); ?></em>
					</p>
					
					<p>
						<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
						<textarea id="description" name="description"><?php echo Input::post('description'); ?></textarea>
						
						<em><?php echo __('posts.description_explain', 'A brief outline of what your post is about.'); ?></em>
					</p>
					
					<p>
						<label><?php echo __('posts.status', 'Status'); ?>:</label>
						<select id="status" name="status">
							<?php foreach(array(
								'published' => __('posts.published', 'published'),
								'draft' => __('posts.draft', 'draft'), 
								'archived' => __('posts.archived', 'archived')
							) as $value => $status): ?>
							<?php $selected = (Input::post('status') == $value) ? ' selected' : ''; ?>
							<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
								<?php echo $status; ?>
							</option>
							<?php endforeach; ?>
						</select>
						
						<em><?php echo __('posts.status_explain', 'Statuses: live (published), pending (draft), or hidden (archived).'); ?></em>
					</p>
					
					<p>
						<label><?php echo __('posts.category', 'Category'); ?>:</label>
						<select id="status" name="status">
							<?php foreach(Categories::list_all() as $cat): ?>
							<?php $selected = (Input::post('category') == $cat->slug) ? ' selected' : ''; ?>
							<option value="<?php echo $cat->slug; ?>"<?php echo $selected; ?>>
								<?php echo $cat->title; ?>
							</option>
							<?php endforeach; ?>
						</select>
						
						<em><?php echo __('posts.status_explain', 'Statuses: live (published), pending (draft), or hidden (archived).'); ?></em>
					</p>
				
					<p>
						<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
						<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::post('comments')) echo ' checked'; ?>>
						<em><?php echo __('posts.allow_comments_explain', 'This will allow users to comment on your posts.'); ?></em>
					</p>
				</div>
				
				<div class="media-upload">
					<p>
						<label for="css"><?php echo __('posts.custom_css', 'Custom CSS'); ?>:</label>
						<textarea id="css" name="css"><?php echo Input::post('css'); ?></textarea>
						
						<em><?php echo __('posts.custom_css_explain', 'Custom CSS. Will be wrapped in a <code>&lt;style&gt;</code> block.'); ?></em>
					</p>
	
					<p>
						<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
						<textarea id="js" name="js"><?php echo Input::post('js'); ?></textarea>
						
						<em><?php echo __('posts.custom_js_explain', 'Custom Javascript. Will be wrapped in a <code>&lt;script&gt;</code> block.'); ?></em>
					</p>
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
		</div>
	</div>
</form>