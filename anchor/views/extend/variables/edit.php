<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.editing_variable', $variable->user_key); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/variables/edit/' . $variable->key); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="label-name"><?php echo __('extend.name'); ?>:</label>
				<?php echo Form::text('key', Input::previous('key', $variable->user_key), array('id' => 'label-name')); ?>
				<em><?php echo __('extend.name_explain'); ?></em>
			</p>

			<p>
				<label for="label-value"><?php echo __('extend.value'); ?>:</label>
				<?php echo Form::textarea('value', Input::previous('value', $variable->value), array('cols' => 20, 'id' => 'label-value')); ?>
				<em><?php echo __('extend.value_explain'); ?></em>
				<summary><?php echo __('extend.value_code_snipet', $variable->user_key); ?></summary>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.update'), array('class' => 'btn', 'type' => 'submit')); ?>

			<?php echo Html::link('admin/extend/variables' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>

			<?php echo Html::link('admin/extend/variables/delete/' . $variable->key,
				__('global.delete'), array('class' => 'btn delete red')); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>