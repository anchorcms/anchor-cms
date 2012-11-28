<?php echo $header; ?>

<hgroup class="wrap">
	<h1>Create a custom field</h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('extend/fields/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="type"><?php echo __('extend.field_extend', 'Extend'); ?>:</label>
				<select id="type" name="type">
					<?php foreach(array('post', 'page') as $type): ?>
					<?php $selected = (Input::old('type') == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="field"><?php echo __('extend.field_type', 'Type'); ?>:</label>
				<select id="field" name="field">
					<?php foreach(array('text', 'html', 'image', 'file') as $type): ?>
					<?php $selected = (Input::old('field') == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="key"><?php echo __('extend.field_key', 'Unique Key'); ?>:</label>
				<input id="key" name="key" value="<?php echo Input::old('key'); ?>">
			</p>

			<p>
				<label for="label"><?php echo __('extend.field_label', 'Label'); ?>:</label>
				<input id="label" name="label" value="<?php echo Input::old('label'); ?>">
			</p>

			<p class="hide attributes_type">
				<label for="attributes_type"><?php echo __('extend.attribute_type', 'File Types'); ?>:</label>
				<input id="attributes_type" name="attributes[type]"
					value="<?php echo Input::old('attributes.type'); ?>">
			</p>

			<p class="hide attributes_width">
				<label for="attributes_size_width"><?php echo __('extend.attributes_size_width', 'Image Width'); ?>:</label>
				<input id="attributes_size_width" name="attributes[size][width]"
					value="<?php echo Input::old('attributes.size.width'); ?>">
			</p>

			<p class="hide attributes_height">
				<label for="attributes_size_height"><?php echo __('extend.attributes_size_height', 'Image Height'); ?>:</label>
				<input id="attributes_size_height" name="attributes[size][height]"
					value="<?php echo Input::old('attributes.size.height'); ?>">
			</p>
		</fieldset>

		<aside class="buttons">
			<button class="btn" type="submit"><?php echo __('extend.save', 'Save'); ?></button>
		</aside>
	</form>
</section>

<script src="<?php echo admin_asset('js/custom-fields.js'); ?>"></script>

<?php echo $footer; ?>