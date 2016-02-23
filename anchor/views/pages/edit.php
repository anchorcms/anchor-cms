<?php echo $header; ?>

<form method="post" action="<?php echo Uri::to('admin/pages/edit/' . $page->id); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">
		<div class="wrap page">
			<?php echo $messages; ?>

			<?php echo Form::text('title', Input::previous('title', $page->title), array(
				'placeholder' => __('pages.title'),
				'autocomplete'=> 'off',
				'autofocus' => 'true'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('global.save'), array(
					'type' => 'submit',
					'class' => 'btn'
				)); ?>
				<a class="btn autosave-action autosave-label secondary" style="width: 154px;">Autosave: Off</a>
				<?php echo Form::button(__('pages.redirect'), array(
					'class' => 'btn secondary redirector'
				)); ?>

				<?php echo Html::link('admin/pages' , __('global.cancel'), array(
					'class' => 'btn cancel blue'
				)); ?>

				<?php
				if($deletable == true) {
					echo Html::link('admin/pages/delete/' . $page->id, __('global.delete'), array(
						'class' => 'btn delete red'
					));
				}
				?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="redirect <?php echo ($page->redirect) ? 'show' : ''; ?>">
		<div class="wrap">
			<?php echo Form::text('redirect', Input::previous('redirect', $page->redirect), array(
				'placeholder' => __('pages.redirect_url')
			)); ?>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('markdown', Input::previous('markdown', $page->markdown), array(
				'placeholder' => __('pages.content_explain')
			)); ?>

			<?php echo $editor; ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			<p>
				<label for="label-show_in_menu"><?php echo __('pages.show_in_menu'); ?>:</label>
				<?php echo Form::checkbox('show_in_menu', 1, Input::previous('show_in_menu', $page->show_in_menu) == 1, array('id' => 'label-show_in_menu')); ?>
				<em><?php echo __('pages.show_in_menu_explain'); ?></em>
			</p>
			<p>
				<label for="label-name"><?php echo __('pages.name'); ?>:</label>
				<?php echo Form::text('name', Input::previous('name', $page->name), array('id' => 'label-name')); ?>
				<em><?php echo __('pages.name_explain'); ?></em>
			</p>
			<p>
				<label for="label-slug"><?php echo __('pages.slug'); ?>:</label>
				<?php echo Form::text('slug', Input::previous('slug', $page->slug), array('id' => 'label-slug')); ?>
				<em><?php echo __('pages.slug_explain'); ?></em>
			</p>
			<p>
				<label for="label-status"><?php echo __('pages.status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status', $page->status), array('id' => 'label-status')); ?>
				<em><?php echo __('pages.status_explain'); ?></em>
			</p>
			<p>
				<label for="label-parent"><?php echo __('pages.parent'); ?>:</label>
				<?php echo Form::select('parent', $pages, Input::previous('parent', $page->parent), array('id' => 'label-parent')); ?>
				<em><?php echo __('pages.parent_explain'); ?></em>
			</p>
			<?php if(count($pagetypes) > 0): ?>
			<p>
				<label for="pagetype"><?php echo __('pages.pagetype'); ?>:</label>
				<select id="pagetype" name="pagetype">
					<?php foreach($pagetypes as $pagetype): ?>
					<?php $selected = (Input::previous('pagetype') == $pagetype->key || $page->pagetype == $pagetype->key) ? ' selected="selected"' : ''; ?>
					<option value="<?php echo $pagetype->key; ?>" <?php echo $selected; ?>><?php echo $pagetype->value; ?></option>
					<?php endforeach; ?>
				</select>
				<em><?php echo __('pages.pagetype_explain'); ?></em>
			</p>
			<?php endif; ?>
			<div id="extended-fields">
			<?php foreach($fields as $field): ?>
				<p>
					<label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
					<?php echo Extend::html($field); ?>
				</p>
			<?php endforeach; ?>
			</div>
		</div>
	</fieldset>
</form>

<script src="<?php echo asset('anchor/views/assets/js/redirect.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/text-resize.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/editor.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/change-saver.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<script>
	$('textarea[name=markdown]').editor();
	$('#pagetype').on('change', function() {
		var $this = $(this);
		$.post("<?php echo Uri::to('admin/get_fields'); ?>", {
			id: <?php echo $page->id; ?>,
			pagetype: $this.val(),
			token: "<?php echo $token; ?>"
		}, function(res){
			res = JSON.parse(res);
			$('#extended-fields').html(res.html);
			$('input[name="token"]').replaceWith(res.token);
		});
	});
</script>

<?php echo $footer; ?>
