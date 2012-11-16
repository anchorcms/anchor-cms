<?php echo $header; ?>

<section class="login content">

	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('reset/' . $key); ?>">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<p><label for="pass"><?php echo __('users.new_password', 'New Password'); ?>:</label>
			<input placeholder="<?php echo __('users.new_password', 'New Password'); ?>" type="password" name="pass" id="pass"></p>

			<p class="buttons">
			<button type="submit"><?php echo __('users.submit', 'Submit'); ?></button></p>
		</fieldset>
	</form>

</section>

<?php echo $footer; ?>