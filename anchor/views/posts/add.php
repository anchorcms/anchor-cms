<?php echo $header; ?>

<form method="post" action="<?php echo url('posts/add'); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<header class="header">
		<div class="wrap">
    		<input autofocus autocomplete="off" tabindex="1" placeholder="Post title" id="title" name="title"
    			value="<?php echo Input::old('title'); ?>">

    		<p class="buttons">
    			<button tabindex="3" type="submit"><?php echo __('posts.create', 'Create'); ?></button>
    		</p>

    		<?php echo $messages; ?>
    	</div>
	</header>

	<div class="prevue">
	    <div class="wrap"></div>
	</div>

	<fieldset id="content">
		<p>
			<textarea tabindex="2" id="post-content" placeholder="<?php echo __('posts.content_explain', 'Just write.'); ?>" name="html"><?php echo Input::old('html'); ?></textarea>
		</p>
	</fieldset>
	<fieldset id="post-data">
		<div class="wrap">
			<!--<p>
				<label for="created"><?php echo __('posts.date', 'Date'); ?>:</label>
				<input type="date" id="created" autocomplete="off" name="created" value="<?php echo Input::old('created', date('jS M Y, h:i')); ?>">

				<em><?php echo __('posts.date_explain', 'The date your post will be published. Uses <code><a href="http://php.net/manual/en/function.strtotime.php">strtotime()</a></code>.'); ?></em>
			</p>-->

			<p>
				<label for="slug"><?php echo __('posts.slug', 'Slug'); ?>:</label>
				<input id="slug" autocomplete="off" name="slug" value="<?php echo Input::old('slug'); ?>">
			</p>

			<p>
				<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
				<textarea id="description" name="description"><?php echo Input::old('description'); ?></textarea>
			</p>

			<p>
				<label for="status"><?php echo __('posts.status', 'Status'); ?>:</label>
				<select id="status" name="status">
					<?php foreach(array(
						'published' => __('posts.published', 'published'),
						'draft' => __('posts.draft', 'draft'),
						'archived' => __('posts.archived', 'archived')
					) as $value => $status): ?>
					<?php $selected = (Input::old('status') == $value) ? ' selected' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
						<?php echo $status; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="category"><?php echo __('posts.category', 'Category'); ?>:</label>
				<select id="category" name="category">
					<?php foreach($categories as $cat): ?>
					<?php $selected = (Input::old('category') == $cat->slug) ? ' selected' : ''; ?>
					<option value="<?php echo $cat->slug; ?>"<?php echo $selected; ?>>
						<?php echo $cat->title; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
				<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::old('comments')) echo ' checked'; ?>>
			</p>

			<div class="media-upload">
				<p>
					<label for="css"><?php echo __('posts.custom_css', 'Custom CSS'); ?>:</label>
					<textarea id="css" name="css"><?php echo Input::old('css'); ?></textarea>
				</p>

				<p>
					<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
					<textarea id="js" name="js"><?php echo Input::old('js'); ?></textarea>
				</p>
			</div>
		</div>


		<!--<legend><?php echo __('posts.custom_fields', 'Custom Fields'); ?></legend>
		<em><?php echo __('posts.custom_fields_explain', 'Create custom fields here.'); ?></em>

		<div id="fields">
			<?php foreach(Input::old('field', array()) as $data => $value): ?>
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

<?php echo $footer; ?>