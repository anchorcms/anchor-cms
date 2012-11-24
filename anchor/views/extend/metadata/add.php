<?php echo $header; ?>

<h1>Create a custom field</h1>

<section class="content">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('extend/metadata/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">

		</fieldset>

		<p class="buttons">
			<button type="submit"><?php echo __('extend.save', 'Save'); ?></button>
		</p>

	</form>
</section>

<?php echo $footer; ?>