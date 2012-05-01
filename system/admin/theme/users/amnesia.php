<h1><?php echo __('users.recover_password', 'Recover Password'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>">

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">

		<fieldset>
			
			<p>
				<label for="email"><?php echo __('users.email', 'Email'); ?>:</label>
				<input autocapitalize="off" name="email" id="email" value="<?php echo Input::post('email'); ?>">
			</p>

			<p class="buttons">
				<button type="submit"><?php echo __('users.recover', 'Recover'); ?></button>
				<a href="<?php echo Url::make(); ?>">Back to <?php echo Config::get('metadata.sitename'); ?></a>
			</p>
		</fieldset>
	</form>

</section>

