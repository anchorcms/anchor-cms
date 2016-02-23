<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.create_pagetype'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/pagetypes/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('extend.name'); ?>:</label>
				<?php echo Form::text('value', Input::previous('value')); ?>
				<em><?php echo __('extend.name_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('pages.slug'); ?>:</label>
				<?php echo Form::text('key', Input::previous('key')); ?>
				<em><?php echo __('pages.slug_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('class' => 'btn', 'type' => 'submit')); ?>

			<?php echo Html::link('admin/extend/pagetypes' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>