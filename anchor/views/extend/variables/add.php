<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.create_variable'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/variables/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="label-name"><?php echo __('extend.name'); ?>:</label>
				<?php echo Form::text('key', Input::previous('key'), array('id' => 'label-name')); ?>
				<em><?php echo __('extend.name_explain'); ?></em>
			</p>

			<p>
				<label for="label-value"><?php echo __('extend.value'); ?>:</label>
				<?php echo Form::textarea('value', Input::previous('value'), array('cols' => 20, 'id' => 'label-value')); ?>
				<em><?php echo __('extend.value_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('class' => 'btn', 'type' => 'submit')); ?>

			<?php echo Html::link('admin/extend/variables' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>