<h1>Recover Password</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" >
		<fieldset>
			
			<p>
			    <label for="email">Email:</label>
			    <input autocapitalize="off" name="email" id="email" value="<?php echo Input::post('email'); ?>">
			</p>

			<p class="buttons">
			    <button type="submit">Recover</button>
			    <a href="<?php echo Url::make(); ?>">Back to <?php echo Config::get('metadata.sitename'); ?></a>
			</p>
		</fieldset>
	</form>

</section>

