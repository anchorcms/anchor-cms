<?php echo $header; ?>

<form method="post" action="<?php echo admin_url('posts/edit/' . $article->id); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">
		<div class="wrap">
			<?php echo $messages; ?>

			<?php echo Form::text('title', Input::old('title', $article->title), array(
				'placeholder' => __('posts.title', 'Post title'),
				'autocomplete'=> 'off'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('posts.save', 'Save Changes'), array(
					'type' => 'submit',
					'class' => 'btn'
				)); ?>

				<?php echo Html::link(admin_url('posts/delete/' . $article->id), __('posts.delete', 'Delete'), array(
					'class' => 'btn delete red'
				)); ?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('html', Input::old('html', $article->html), array(
				'placeholder' => __('posts.content_explain', 'Just write.')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			<p>
				<label><?php echo __('posts.slug', 'Slug'); ?>:</label>
				<?php echo Form::text('slug', Input::old('slug', $article->slug)); ?>
				<em><?php echo __('posts.slug_explain'); ?></em>
			</p>

			<p>
				<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
				<?php echo Form::textarea('description', Input::old('description', $article->description)); ?>
			</p>

			<p>
				<label for="status"><?php echo __('posts.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::old('status', $article->status)); ?>
				<em><?php echo __('posts.status_explain'); ?></em>
			</p>

			<p>
				<label for="category"><?php echo __('posts.category', 'Category'); ?>:</label>
				<?php echo Form::select('category', $categories, Input::old('category', $article->category)); ?>
			</p>

			<p>
				<label><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
				<?php echo Form::checkbox('comments', 1, Input::old('comments', $article->comments) == 1); ?>
			</p>

			<p>
				<label><?php echo __('posts.custom_css', 'Custom CSS'); ?>:</label>
				<?php echo Form::textarea('css', Input::old('css', $article->css)); ?>
			</p>

			<p>
				<label for="js"><?php echo __('posts.custom_js', 'Custom JS'); ?>:</label>
				<?php echo Form::textarea('js', Input::old('js', $article->js)); ?>
			</p>

			<?php foreach($fields as $field): ?>
			<p>
				<label for="<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>
			</p>
			<?php endforeach; ?>
		</div>
	</fieldset>

	<div class="media-upload"></div>
</form>

<script src="<?php echo admin_asset('js/slug.js'); ?>"></script>
<script src="<?php echo admin_asset('js/dragdrop.js'); ?>"></script>
<script src="<?php echo admin_asset('js/focus-mode.js'); ?>"></script>
<script src="<?php echo admin_asset('js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>