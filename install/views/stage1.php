<?php render('layout/header'); ?>

<div class="content">
	<h2>Welcome to Anchor.</h2>

	<p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve 
	found the right place. Simply fill in the details below, and you&rsquo;ll have your 
	new blog set up in no time.</p>

	<form method="post" action="index.php">

		<fieldset>
			<p><label>Language<br>
			<select name="language">
				<option value="en" selected>English</option>
			</select></label></p>
		</fieldset>

		<p><button type="submit">Continue</button></p>

	</form>
</div>

<?php render('layout/footer'); ?>