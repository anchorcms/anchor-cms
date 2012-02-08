<h1>Recover Password</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" >
		<fieldset>
			
			<p>
			    <label for="email">Email:</label>
			    <input autocapitalize="off" name="email" id="email" value="<?php echo Input::post('email'); ?>">
			</p>

			<p class="buttons">
			    <button type="submit">Recover</button>
			    <a href="<?php echo base_url(); ?>">Back to <?php echo site_name(); ?></a>
			</p>
		</fieldset>
	</form>

</section>

