<h1>Site metadata</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="sitename">Site name:</label>
    			<input id="sitename" name="sitename" value="<?php echo Input::post('name', $metadata->sitename); ?>">
    			
    			<em>Your site&rsquo;s name.</em>
    		</p>

			<p>
			    <label for="description">Site description:</label>
			    <textarea id="description" name="description"><?php echo Input::post('description', $metadata->description); ?></textarea>
			    
			    <em>A short paragraph to describe your site.</em>
			</p>

			<p>
			    <label>Home Page:</label>
    			<select id="home_page" name="home_page">
    				<?php foreach($pages as $page): ?>
    				<?php $selected = (Input::post('home_page', $metadata->home_page) == $page->id) ? ' selected' : ''; ?>
    				<option value="<?php echo $page->id; ?>"<?php echo $selected; ?>>
    					<?php echo $page->name; ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Your current home page.</em>
			</p>

			<p>
			    <label>Posts Page:</label>
    			<select id="posts_page" name="posts_page">
    				<?php foreach($pages as $page): ?>
    				<?php $selected = (Input::post('posts_page', $metadata->posts_page) == $page->id) ? ' selected' : ''; ?>
    				<option value="<?php echo $page->id; ?>"<?php echo $selected; ?>>
    					<?php echo $page->name; ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Your page that will show your posts.</em>
			</p>

			<p>
				<label for="posts_per_page">Posts per page:</label>
				<input id="posts_per_page" name="posts_per_page" value="<?php echo Input::post('posts_per_page', $metadata->posts_per_page); ?>">
				
				<em>The number of posts to display per page.</em>
			</p>
			
			<p>
				<label>Current theme:</label>
				<select id="theme" name="theme">
					<?php foreach($themes as $theme => $about): ?>
					<?php $selected = (Input::post('theme', $metadata->theme) == $theme) ? ' selected' : ''; ?>
					<option value="<?php echo $theme; ?>"<?php echo $selected; ?>>
						<?php echo $about['name']; ?> by <?php echo $about['author']; ?>
					</option>
					<?php endforeach; ?>
				</select>

				<em>Your current theme.</em>
			</p>

			<p>
				<label for="auto_published_comments">Auto publish comments:</label>
				<?php $checked = Input::post('auto_published_comments', $metadata->auto_published_comments) ? ' checked' : ''; ?>
				<input name="auto_published_comments" type="checkbox" value="1"<?php echo $checked; ?>>
			</p>

			<p>
				<label for="twitter">Twitter:</label>
				<input id="twitter" name="twitter" value="<?php echo Input::post('twitter', $metadata->twitter); ?>">
				
				<em>Your twitter account. Displayed as @<span id="output"><?php echo $metadata->twitter; ?></span>.</em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button name="save" type="submit">Save changes</button>
		</p>
	</form>

</section>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('assets/js/mootools.js'); ?>"><\/script>');</script>
<script src="<?php echo theme_url('assets/js/helpers.js'); ?>"></script>
<script>
	(function() {
		var tweet = $('twitter'), output = $('output');

		// call the function to init the input text
		formatTwitter(tweet, output);

		// bind to input
		tweet.addEvent('keyup', function() {formatTwitter(tweet, output)});
	}());
</script>

