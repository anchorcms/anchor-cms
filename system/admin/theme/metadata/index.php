
<h1><?php echo __('metadata.metadata', 'Site metadata'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">
		
		<fieldset>
			<p>
				<label for="sitename"><?php echo __('metadata.sitename', 'Site name'); ?>:</label>
				<input id="sitename" name="sitename" value="<?php echo Input::post('name', $metadata->sitename); ?>">
				
				<em><?php echo __('metadata.sitename_explain', 'Your site&rsquo;s name.'); ?></em>
			</p>

			<p>
				<label for="description"><?php echo __('metadata.sitedescription', 'Site description'); ?>:</label>
				<textarea id="description" name="description"><?php echo Input::post('description', $metadata->description); ?></textarea>
				
				<em><?php echo __('metadata.sitedescription_explain', 'A short paragraph to describe your site.'); ?></em>
			</p>

			<p>
				<label><?php echo __('metadata.homepage', 'Home Page'); ?>:</label>
				<select id="home_page" name="home_page">
					<?php foreach($pages as $page): ?>
					<?php $selected = (Input::post('home_page', $metadata->home_page) == $page->id) ? ' selected' : ''; ?>
					<option value="<?php echo $page->id; ?>"<?php echo $selected; ?>>
						<?php echo $page->name; ?>
					</option>
					<?php endforeach; ?>
				</select>
				
				<em><?php echo __('metadata.homepage_explain', 'Your current home page.'); ?></em>
			</p>

			<p>
				<label><?php echo __('metadata.postspage', 'Posts Page'); ?>:</label>
				<select id="posts_page" name="posts_page">
					<?php foreach($pages as $page): ?>
					<?php $selected = (Input::post('posts_page', $metadata->posts_page) == $page->id) ? ' selected' : ''; ?>
					<option value="<?php echo $page->id; ?>"<?php echo $selected; ?>>
						<?php echo $page->name; ?>
					</option>
					<?php endforeach; ?>
				</select>
				
				<em><?php echo __('metadata.postspage_explain', 'Your page that will show your posts.'); ?></em>
			</p>

			<p>
				<label for="posts_per_page"><?php echo __('metadata.posts_per_page', 'Posts per page'); ?>:</label>
				<input id="posts_per_page" name="posts_per_page" value="<?php echo Input::post('posts_per_page', $metadata->posts_per_page); ?>">
				
				<em><?php echo __('metadata.posts_per_page_explain', 'The number of posts to display per page.'); ?></em>
			</p>
			
			<p>
				<label><?php echo __('metadata.current_theme', 'Current theme'); ?>:</label>
				<select id="theme" name="theme">
					<?php foreach($themes as $theme => $about): ?>
					<?php $selected = (Input::post('theme', $metadata->theme) == $theme) ? ' selected' : ''; ?>
					<option value="<?php echo $theme; ?>"<?php echo $selected; ?>>
						<?php echo $about['name']; ?> by <?php echo $about['author']; ?>
					</option>
					<?php endforeach; ?>
				</select>

				<em><?php echo __('metadata.current_theme_explain', 'Your current theme.'); ?></em>
			</p>

			<p>
				<label for="auto_published_comments"><?php echo __('metadata.auto_publish_comments', 'Auto publish comments'); ?>:</label>
				<?php $checked = Input::post('auto_published_comments', $metadata->auto_published_comments) ? ' checked' : ''; ?>
				<input id="auto_published_comments" name="auto_published_comments" type="checkbox" value="1"<?php echo $checked; ?>>
			</p>

			<p>
				<label for="twitter"><?php echo __('metadata.twitter', 'Twitter'); ?>:</label>
				<input id="twitter" name="twitter" value="<?php echo Input::post('twitter', $metadata->twitter); ?>">
				
				<em><?php echo __('metadata.twitter_explain', 'Your twitter account.'); ?></em>
			</p>

			<p>
				<label for="gosquared"><?php echo __('metadata.gosquared', 'GoSquared ID'); ?>:</label>
				<input id="gosquared" name="gosquared" placeholder="GSN-000000-A" value="<?php echo Input::post('gosquared', $metadata->gosquared); ?>">
				
				<em><?php echo __('metadata.gosquared_explain', 'The site ID for your GoSquared (real-time web analytics) account. You can access your analytics <a href="//home.gosquared.com">here</a>, or sign up for a free trial <a href="//gosquared.com/join">here</a>.'); ?></em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button name="save" type="submit"><?php echo __('metadata.save', 'Save changes'); ?></button>
		</p>
	</form>

</section>
