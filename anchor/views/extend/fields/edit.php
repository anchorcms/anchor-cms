<?php echo $header; ?>

<hgroup class="wrap">
	<h1>Editing &ldquo;<?php echo Str::truncate($field->label, 4); ?>&rdquo;</h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('extend/fields/edit/' . $field->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="type"><?php echo __('extend.field_extend', 'Extend'); ?>:</label>
				<select id="type" name="type">
					<?php foreach(array('post', 'page') as $type): ?>
					<?php $selected = (Input::old('type', $field->type) == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="field"><?php echo __('extend.field_type', 'Type'); ?>:</label>
				<select id="field" name="field">
					<?php foreach(array('text', 'html', 'image', 'file') as $type): ?>
					<?php $selected = (Input::old('field', $field->field) == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="key"><?php echo __('extend.field_key', 'Unique Key'); ?>:</label>
				<input id="key" name="key" value="<?php echo Input::old('key', $field->key); ?>">
			</p>

			<p>
				<label for="label"><?php echo __('extend.field_label', 'Label'); ?>:</label>
				<input id="label" name="label" value="<?php echo Input::old('label', $field->label); ?>">
			</p>

			<p class="hide attributes_type">
				<label for="attributes_type"><?php echo __('extend.attribute_type', 'File Types'); ?>:</label>

				<?php $value = isset($field->attributes->type) ? $field->attributes->type : ''; ?>
				<input id="attributes_type" name="attributes[type]"
					value="<?php echo Input::old('attributes.type', $value); ?>">
			</p>

			<p class="hide attributes_width">
				<label for="attributes_size_width"><?php echo __('extend.attributes_size_width', 'Image Width'); ?>:</label>

				<?php $value = isset($field->attributes->size->width) ? $field->attributes->size->width : ''; ?>
				<input id="attributes_size_width" name="attributes[size][width]"
					value="<?php echo Input::old('attributes.size.width', $value); ?>">
			</p>

			<p class="hide attributes_height">
				<label for="attributes_size_height"><?php echo __('extend.attributes_size_height', 'Image Height'); ?>:</label>

				<?php $value = isset($field->attributes->size->height) ? $field->attributes->size->height : ''; ?>
				<input id="attributes_size_height" name="attributes[size][height]"
					value="<?php echo Input::old('attributes.size.height', $value); ?>">
			</p>
		</fieldset>

		<aside class="buttons">
			<button class="btn" type="submit"><?php echo __('extend.update', 'Update'); ?></button>
			<a class="btn delete red" href="<?php echo admin_url('extend/fields/delete/' . $field->id); ?>">
				<?php echo __('extend.delete', 'Delete'); ?></a>
		</aside>
	</form>
</section>

<script src="<?php echo admin_asset('js/custom-fields.js'); ?>"></script>

<?php echo $footer; ?>