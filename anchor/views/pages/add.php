<?php echo $header; ?>

<form method="post" action="<?php echo Uri::to('admin/pages/add'); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">
		<div class="wrap">
			<?php echo $messages; ?>

			<?php echo Form::text('title', Input::previous('title'), array(
				'placeholder' => __('pages.title'),
				'autocomplete'=> 'off',
				'autofocus' => 'true'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('global.save'), array(
					'type' => 'submit',
					'class' => 'btn'
				)); ?>

				<?php echo Form::button(__('pages.redirect'), array(
					'class' => 'btn secondary'
				)); ?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="redirect">
		<div class="wrap">
			<?php echo Form::text('redirect', Input::previous('redirect'), array(
				'placeholder' => __('pages.redirect_url')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('content', Input::previous('content'), array(
				'placeholder' => __('pages.content_explain')
			)); ?>

			<?php echo $editor; ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			<p>
				<label><?php echo __('pages.show_in_menu'); ?>:</label>
				<?php echo Form::checkbox('show_in_menu', 1, Input::previous('show_in_menu', 0) == 1); ?>
				<em><?php echo __('pages.show_in_menu_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('pages.name'); ?>:</label>
				<?php echo Form::text('name', Input::previous('name')); ?>
				<em><?php echo __('pages.name_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('pages.slug'); ?>:</label>
				<?php echo Form::text('slug', Input::previous('slug')); ?>
				<em><?php echo __('pages.slug_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('pages.status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status')); ?>
				<em><?php echo __('pages.status_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('pages.parent'); ?>:</label>
				<?php echo Form::select('parent', $pages, Input::previous('parent')); ?>
				<em><?php echo __('pages.parent_explain'); ?></em>
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
<script src="<?php echo asset('anchor/views/assets/js/page-name.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/redirect.js'); ?>"></script>
<!--<script src="<?php echo asset('anchor/views/assets/js/focus-mode.js'); ?>"></script>-->
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/editor.js'); ?>"></script>
<script>
	$('textarea[name=content]').editor();
</script>

<?php echo $footer; ?>