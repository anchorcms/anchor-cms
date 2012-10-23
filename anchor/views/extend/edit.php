<?php echo $header; ?>

<h1>Editing &ldquo;<?php echo Str::truncate($field->label, 4); ?>&rdquo;</h1>

<?php echo $messages; ?>

<section class="content">

	<form method="post" action="<?php echo url('extend/edit/' . $field->id); ?>" novalidate>

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

			<?php if($field->attributes): ?>

			<?php $attributes = Json::decode($field->attributes); ?>

				<?php if(isset($attributes->type)): ?>
				<p>
					<label for="attributes_type"><?php echo __('extend.attribute_type', 'File Types'); ?>:</label>
					<input id="attributes_type" name="attributes[type]"
						value="<?php echo Input::old('attributes.type', implode(', ', $attributes->type)); ?>">
				</p>
				<?php endif; ?>

				<?php if(isset($attributes->size->width)): ?>
				<p>
					<label for="attributes_size_width"><?php echo __('extend.attributes_size_width', 'Image Width'); ?>:</label>
					<input id="attributes_size_width" name="attributes[size][width]"
						value="<?php echo Input::old('attributes.size.width', $attributes->size->width); ?>">
				</p>
				<?php endif; ?>

				<?php if(isset($attributes->size->height)): ?>
				<p>
					<label for="attributes_size_height"><?php echo __('extend.attributes_size_height', 'Image Height'); ?>:</label>
					<input id="attributes_size_height" name="attributes[size][height]"
						value="<?php echo Input::old('attributes.size.height', $attributes->size->height); ?>">
				</p>
				<?php endif; ?>

			<?php endif; ?>
		</fieldset>

		<p class="buttons">
			<button type="submit"><?php echo __('extend.update', 'Update'); ?></button>
		</p>

	</form>
</section>

<?php echo $footer; ?>