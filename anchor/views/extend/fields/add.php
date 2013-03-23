<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.create_field'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/fields/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="type"><?php echo __('extend.type'); ?>:</label>
				<select id="type" name="type">
					<?php foreach(array('post', 'page') as $type): ?>
					<?php $selected = (Input::previous('type') == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
				<em><?php echo __('extend.type_explain'); ?></em>
			</p>

			<p>
				<label for="field"><?php echo __('extend.field'); ?>:</label>
				<select id="field" name="field">
					<?php foreach(array('text', 'html', 'image', 'file') as $type): ?>
					<?php $selected = (Input::previous('field') == $type) ? ' selected' : ''; ?>
					<option<?php echo $selected; ?>><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
				<em><?php echo __('extend.field_explain'); ?></em>
			</p>

			<p>
				<label for="key"><?php echo __('extend.key'); ?>:</label>
				<input id="key" name="key" value="<?php echo Input::previous('key'); ?>">
				<em><?php echo __('extend.key_explain'); ?></em>
			</p>

			<p>
				<label for="label"><?php echo __('extend.label'); ?>:</label>
				<input id="label" name="label" value="<?php echo Input::previous('label'); ?>">
				<em><?php echo __('extend.label_explain'); ?></em>
			</p>

			<p class="hide attributes_type">
				<label for="attributes_type"><?php echo __('extend.attribute_type'); ?>:</label>
				<input id="attributes_type" name="attributes[type]" value="<?php echo Input::previous('attributes.type'); ?>">
				<em><?php echo __('extend.attribute_type_explain'); ?></em>
			</p>

			<p class="hide attributes_width">
				<label for="attributes_size_width"><?php echo __('extend.attributes_size_width'); ?>:</label>
				<input id="attributes_size_width" name="attributes[size][width]"
					value="<?php echo Input::previous('attributes.size.width'); ?>">

				<em><?php echo __('extend.attributes_size_width_explain'); ?></em>
			</p>

			<p class="hide attributes_height">
				<label for="attributes_size_height"><?php echo __('extend.attributes_size_height'); ?>:</label>
				<input id="attributes_size_height" name="attributes[size][height]"
					value="<?php echo Input::previous('attributes.size.height'); ?>">

				<em><?php echo __('extend.attributes_size_height_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<button class="btn" type="submit"><?php echo __('global.save'); ?></button>
		</aside>
	</form>
</section>

<script src="<?php echo asset('anchor/views/assets/js/custom-fields.js'); ?>"></script>

<?php echo $footer; ?>