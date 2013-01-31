<?php echo $header; ?>

<form method="post" action="<?php echo admin_url('pages/edit/' . $page->id); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">
		<div class="wrap">
			<?php echo $messages; ?>

			<?php echo Form::text('title', Input::old('title', $page->title), array(
				'placeholder' => __('pages.title', 'Page title'),
				'autocomplete'=> 'off'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('pages.save', 'Save Changes'), array(
					'type' => 'submit',
					'class' => 'btn'
				)); ?>

				<?php echo Form::button(__('pages.redirect', 'Redirect'), array(
					'class' => 'btn secondary'
				)); ?>

				<?php echo Html::link(admin_url('pages/delete/' . $page->id), __('posts.delete', 'Delete'), array(
					'class' => 'btn delete red'
				)); ?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="redirect <?php echo ($page->redirect) ? 'show' : ''; ?>">
		<div class="wrap">
			<?php echo Form::text('redirect', Input::old('redirect', $page->redirect), array(
				'placeholder' => __('pages.redirect_url', 'Redirect Url')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('content', Input::old('content', $page->content), array(
				'placeholder' => __('pages.content_explain', 'Your page’s content. Uses Markdown.')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			<p>
				<label><?php echo __('pages.name', 'Name'); ?>:</label>
				<?php echo Form::text('name', Input::old('name', $page->name)); ?>
				<em><?php echo __('pages.name_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('pages.slug', 'Slug'); ?>:</label>
				<?php echo Form::text('slug', Input::old('slug', $page->slug)); ?>
				<em><?php echo __('pages.slug_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('pages.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::old('status', $page->status)); ?>
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
