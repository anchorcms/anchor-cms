<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.editing_custom_field', $field->label); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/fields/edit/' . $field->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="type"><?php echo __('extend.type'); ?>:</label>
				<select id="type" name="type">
					<?php foreach(array('post', 'page') as $type): ?>
					<?php $selected = (Input::previous('type', $field->type) == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
				<em><?php echo __('extend.type_explain'); ?></em>
			</p>

			<p>
				<label for="field"><?php echo __('extend.field'); ?>:</label>
				<select id="field" name="field">
					<?php foreach(array('text', 'html', 'image', 'file') as $type): ?>
					<?php $selected = (Input::previous('field', $field->field) == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
				<em><?php echo __('extend.field_explain'); ?></em>
			</p>

			<p>
				<label for="key"><?php echo __('extend.key'); ?>:</label>
				<input id="key" name="key" value="<?php echo Input::previous('key', $field->key); ?>">
				<em><?php echo __('extend.key_explain'); ?></em>
			</p>

			<p>
				<label for="label"><?php echo __('extend.label', 'Label'); ?>:</label>
				<input id="label" name="label" value="<?php echo Input::previous('label', $field->label); ?>">
				<em><?php echo __('extend.label_explain'); ?></em>
			</p>

			<p class="hide attributes_type">
				<label for="attributes_type"><?php echo __('extend.attribute_type'); ?>:</label>

				<?php $value = isset($field->attributes->type) ? $field->attributes->type : ''; ?>
				<input id="attributes_type" name="attributes[type]" value="<?php echo Input::previous('attributes.type', $value); ?>">
				<em><?php echo __('extend.attribute_type_explain'); ?></em>
			</p>

			<p class="hide attributes_width">
				<label for="attributes_size_width"><?php echo __('extend.attributes_size_width'); ?>:</label>

				<?php $value = isset($field->attributes->size->width) ? $field->attributes->size->width : ''; ?>
				<input id="attributes_size_width" name="attributes[size][width]"
					value="<?php echo Input::previous('attributes.size.width', $value); ?>">

				<em><?php echo __('extend.attributes_size_width_explain'); ?></em>
			</p>

			<p class="hide attributes_height">
				<label for="attributes_size_height"><?php echo __('extend.attributes_size_height'); ?>:</label>

				<?php $value = isset($field->attributes->size->height) ? $field->attributes->size->height : ''; ?>
				<input id="attributes_size_height" name="attributes[size][height]"
					value="<?php echo Input::previous('attributes.size.height', $value); ?>">

				<em><?php echo __('extend.attributes_size_height_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<button class="btn" type="submit"><?php echo __('global.update'); ?></button>

			<a class="btn delete red" href="<?php echo Uri::to('admin/extend/fields/delete/' . $field->id); ?>">
				<?php echo __('global.delete'); ?>
			</a>
		</aside>
	</form>
</section>

<script src="<?php echo asset('anchor/views/assets/js/custom-fields.js'); ?>"></script>

<?php echo $footer; ?>