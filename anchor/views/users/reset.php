<?php echo $header; ?>

<section class="login content">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/reset/' . $key); ?>">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="label-pass"><?php echo __('users.new_password'); ?>:</label>
			<input placeholder="<?php echo __('users.new_password'); ?>" type="password" name="pass" id="label-pass"></p>

			<p class="buttons">
			<button type="submit"><?php echo __('global.submit'); ?></button></p>
		</fieldset>
	</form>
</section>

<?php echo $footer; ?>