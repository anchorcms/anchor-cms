<?php echo $header; ?>

<form method="post" action="<?php echo Uri::to('admin/posts/add'); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">
		<div class="wrap">
			<?php echo $messages; ?>

			<?php echo Form::text('title', Input::previous('title'), array(
				'placeholder' => __('posts.title'),
				'autocomplete'=> 'off',
				'autofocus' => 'true'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('global.save'), array(
					'type' => 'submit',
					'class' => 'btn',
					'data-loading' => __('global.saving')
				)); ?>

				<?php echo Html::link('admin/posts' , __('global.cancel'), array(
					'class' => 'btn cancel blue'
				)); ?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('markdown', Input::previous('markdown'), array(
				'placeholder' => __('posts.content_explain')
			)); ?>

			<?php echo $editor; ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			<p>
				<label for="label-slug"><?php echo __('posts.slug'); ?>:</label>
				<?php echo Form::text('slug', Input::previous('slug'), array('id' => 'label-slug')); ?>
				<em><?php echo __('posts.slug_explain'); ?></em>
			</p>
			<p>
				<label for="label-description"><?php echo __('posts.description'); ?>:</label>
				<?php echo Form::textarea('description', Input::previous('description'), array('id' => 'label-description')); ?>
				<em><?php echo __('posts.description_explain'); ?></em>
			</p>
			<p>
				<label for="label-status"><?php echo __('posts.status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status'), array('id' => 'label-status')); ?>
				<em><?php echo __('posts.status_explain'); ?></em>
			</p>
			<p>
				<label for="label-category"><?php echo __('posts.category'); ?>:</label>
				<?php echo Form::select('category', $categories, Input::previous('category'), array('id' => 'label-category')); ?>
				<em><?php echo __('posts.category_explain'); ?></em>
			</p>
			<p>
				<label for="label-comments"><?php echo __('posts.allow_comments'); ?>:</label>
				<?php echo Form::checkbox('comments', 1, Input::previous('comments', 0) == 1, array('id' => 'label-comments')); ?>
				<em><?php echo __('posts.allow_comments_explain'); ?></em>
			</p>
			<p>
				<label for="label-css"><?php echo __('posts.custom_css'); ?>:</label>
				<?php echo Form::textarea('css', Input::previous('css'), array('id' => 'label-css')); ?>
				<em><?php echo __('posts.custom_css_explain'); ?></em>
			</p>
			<p>
				<label for="label-js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
				<?php echo Form::textarea('js', Input::previous('js'), array('id' => 'label-js')); ?>
				<em><?php echo __('posts.custom_js_explain'); ?></em>
			</p>
			<?php foreach($fields as $field): ?>
			<p>
				<label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>
			</p>
			<?php endforeach; ?>
		</div>
	</fieldset>
</form>

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/dragdrop.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/text-resize.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/editor.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<script>
	$('textarea[name=markdown]').editor();
</script>

<?php echo $footer; ?>
