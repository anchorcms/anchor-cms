<?php echo $header; ?>

<form method="post" action="<?php echo admin_url('pages/add'); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">
		<div class="wrap">
			<?php echo $messages; ?>

			<?php echo Form::text('title', Input::old('title'), array(
				'placeholder' => __('pages.title', 'Page title'),
				'autocomplete'=> 'off'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('pages.create', 'Create'), array(
					'type' => 'submit',
					'class' => 'btn'
				)); ?>

				<?php echo Form::button(__('pages.redirect', 'Redirect'), array(
					'class' => 'btn secondary'
				)); ?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="redirect">
		<div class="wrap">
			<?php echo Form::text('redirect', Input::old('redirect'), array(
				'placeholder' => __('pages.redirect_url', 'Redirect Url')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('content', Input::old('content'), array(
				'placeholder' => __('pages.content_explain', 'Your page’s content. Uses Markdown.')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			<p>
				<label><?php echo __('pages.name', 'Name'); ?>:</label>
				<?php echo Form::text('name', Input::old('name')); ?>
				<em><?php echo __('pages.name_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('pages.slug', 'Slug'); ?>:</label>
				<?php echo Form::text('slug', Input::old('slug')); ?>
				<em><?php echo __('pages.slug_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('pages.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::old('status')); ?>
				<em><?php echo __('pages.status_explain'); ?></em>
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

<script src="<?php echo admin_asset('js/slug.js'); ?>"></script>
<script src="<?php echo admin_asset('js/redirect.js'); ?>"></script>

<?php echo $footer; ?>
