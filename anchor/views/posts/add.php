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
			<textarea tabindex="2" id="post-content"
				placeholder="<?php echo __('posts.content_explain', 'Just write.'); ?>"
				name="html"><?php echo Input::old('html'); ?></textarea>
		</p>
	</fieldset>

	<fieldset id="post-data" class="split">
		<div class="wrap">
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
				<?php echo Form::select('status', $statuses, Input::old('status'), array('id' => 'status')); ?>
			</p>

			<p>
				<label for="category"><?php echo __('posts.category', 'Category'); ?>:</label>
				<?php echo Form::select('category', $categories, Input::old('category'), array('id' => 'category')); ?>
			</p>

			<p>
				<label for="template"><?php echo __('posts.template', 'Template'); ?>:</label>
				<?php echo Form::select('template', $templates, Input::old('template'), array('id' => 'template')); ?>
			</p>

			<p>
				<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
				<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::old('comments')) echo ' checked'; ?>>
			</p>

			<p>
				<label for="css"><?php echo __('posts.custom_css', 'Custom CSS'); ?>:</label>
				<textarea id="css" name="css"><?php echo Input::old('css'); ?></textarea>
			</p>

			<p>
				<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
				<textarea id="js" name="js"><?php echo Input::old('js'); ?></textarea>
			</p>

			<?php foreach($fields as $field): ?>
			<p>
				<label for="<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>
			</p>
			<?php endforeach; ?>
		</div>
	</fieldset>
</form>

<?php echo $footer; ?>