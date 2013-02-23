<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('metadata.create_meta', 'Create a new field'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('extend/metadata/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('metadata.custom_name', 'Name'); ?>:</label>
				<?php echo Form::text('name', Input::previous('name')); ?>
			</p>

			<p>
				<label><?php echo __('metadata.custom_value', 'Value'); ?>:</label>
				<?php echo Form::textarea('value', Input::previous('value'), array('cols' => 20)); ?>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('extend.save', 'Save'), array('class' => 'btn', 'type' => 'submit')); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>