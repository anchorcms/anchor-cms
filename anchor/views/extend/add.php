<?php echo $header; ?>

<h1>Create a custom field</h1>

<?php echo $messages; ?>

<section class="content">

	<form method="post" action="<?php echo url('extend/add'); ?>" novalidate>

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
		</fieldset>

		<p class="buttons">
			<button type="submit"><?php echo __('extend.save', 'Save'); ?></button>
		</p>

	</form>
</section>

<?php echo $footer; ?>