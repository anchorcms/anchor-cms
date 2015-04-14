<?php echo $header; ?>

<section class="login content">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/reset/' . $key); ?>">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="label-pass"><?php echo __('users.new_password'); ?>:</label>
			<?php echo Form::password('pass', array('placeholder' => __('users.new_password'), 'id' => 'label-pass')); ?></p>
			<p class="buttons">
			<?php echo Form::button(__('global.submit'), array('type' => 'submit')); ?>
			</p>
		</fieldset>
	</form>
</section>

<?php echo $footer; ?>