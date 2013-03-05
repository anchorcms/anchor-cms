<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.edit_variable', 'Edit Variable'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/variables/edit/' . $variable->key); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('extend.variable_name', 'Key'); ?>:</label>
				<?php echo Form::text('key', Input::previous('key', $variable->user_key)); ?>
			</p>

			<p>
				<label><?php echo __('extend.variable_value', 'Value'); ?>:</label>
				<?php echo Form::textarea('value', Input::previous('value', $variable->value), array('cols' => 20)); ?>
				<summary>Snippet to insert into your template:<br>
				<code><?php echo e('<?php echo site_meta(\'' . $variable->user_key . '\'); ?>'); ?></code></summary>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('extend.update', 'Update'), array('class' => 'btn', 'type' => 'submit')); ?>
			<?php echo Html::link('admin/extend/variables/delete/' . $variable->key,
				__('extend.edit_delete', 'Delete'), array('class' => 'btn delete red')); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>