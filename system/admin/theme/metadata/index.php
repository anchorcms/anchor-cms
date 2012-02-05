<h1>Site metadata</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="sitename">Site name:</label>
    			<input id="sitename" name="sitename" value="<?php echo Input::post('name', site_name()); ?>">
    			
    			<em>Your site&rsquo;s name.</em>
    		</p>
			
			
			<p>
			    <label for="description">Site description:</label>
			    <textarea id="description" name="description"><?php echo Input::post('description', site_description()); ?></textarea>
			    
			    <em>A short paragraph to describe your site.</em>
			</p>
			
			<p>
			    <label>Current theme:</label>
    			<select id="theme" name="theme">
    				<?php foreach(glob(PATH . 'themes/*') as $theme): ?>
    				<option value="<?php echo replace($theme); ?>"<?php if(Input::post('theme', current_theme()) == replace($theme)) echo 'selected'; ?>>
    					<?php echo ucwords(replace($theme)); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Your current theme.</em>
			</p>
			
			<p>
				<label for="twitter">Twitter:</label>
				<input id="twitter" name="twitter" value="<?php echo Input::post('twitter', twitter_account()); ?>">
				
				<em>Your twitter account. Displayed as @<span id="output"><?php echo twitter_account(); ?></span>.</em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button name="save" type="submit">Save changes</button>
		</p>
	</form>

</section>

<script>
	(function() {
		var tweet = document.getElementById('twitter'),
			output = document.getElementById('output'),
			initial = output.value;

			fill = function(e) {
				var me = (typeof e !== 'undefined' ? this : tweet),
				val = me.value;

				output.innerText = val !== '' ? val : initial;
			};

		fill();

		tweet.onkeyup = fill;
	}());
</script>


