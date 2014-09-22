<?php echo $header; ?>

<section class="login content">

	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/amnesia'); ?>">
		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="label-email"><?php echo __('users.email'); ?>:</label>
			<?php echo Form::email('email', Input::previous('email'), array(
				'id' => 'label-email',
				'autocapitalize' => 'off',
				'autofocus' => 'true',
				'placeholder' => __('users.email')
			)); ?></p>

			<p class="buttons">
			    <a href="<?php echo Uri::to('admin/login'); ?>"><?php echo __('users.remembered'); ?></a>
    			<button type="submit"><?php echo __('global.reset'); ?></button>
			</p>
		</fieldset>
	</form>

</section>

<?php echo $footer; ?>