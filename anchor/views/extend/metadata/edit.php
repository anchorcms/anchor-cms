<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('metadata.metadata', 'Site metadata'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('extend/metadata'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('metadata.sitename', 'Site name'); ?>:</label>
				<?php echo Form::text('sitename', Input::old('sitename', $meta['sitename'])); ?>
				<em><?php echo __('metadata.sitename_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('metadata.sitedescription', 'Site description'); ?>:</label>
				<?php echo Form::textarea('description', Input::old('description', $meta['description'])); ?>
				<em><?php echo __('metadata.sitedescription_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('metadata.homepage', 'Home Page'); ?>:</label>
				<?php echo Form::select('home_page', $pages, Input::old('home_page', $meta['home_page'])); ?>
				<em><?php echo __('metadata.homepage_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('metadata.postspage', 'Posts Page'); ?>:</label>
				<?php echo Form::select('posts_page', $pages, Input::old('posts_page', $meta['posts_page'])); ?>
				<em><?php echo __('metadata.postspage_explain'); ?></em>
			</p>

			<p>
				<label for="posts_per_page"><?php echo __('metadata.posts_per_page', 'Posts per page'); ?>:</label>
				<?php echo Form::input('range', 'posts_per_page', Input::old('posts_per_page', $meta['posts_per_page']), array('min' => 1, 'max' => 15)); ?>
				<em><?php echo __('metadata.posts_per_page_explain'); ?></em>
			</p>

			<p>
				<label for="auto_published_comments"><?php echo __('metadata.auto_publish_comments', 'Auto-allow comments'); ?>:</label>
				<?php $checked = Input::old('auto_published_comments', $meta['auto_published_comments']) ? ' checked' : ''; ?>
				<input id="auto_published_comments" name="auto_published_comments" type="checkbox" value="1"<?php echo $checked; ?>>
			</p>

			<p>
				<label for="comment_notifications"><?php echo __('metadata.comment_notifications',
					'Email notification for new comments'); ?>:</label>
				<?php $checked = Input::old('comment_notifications', $meta['comment_notifications']) ? ' checked' : ''; ?>
				<input id="comment_notifications" name="comment_notifications" type="checkbox" value="1"<?php echo $checked; ?>>
			</p>

			<p>
				<label><?php echo __('metadata.comment_moderation_keys', 'Spam keywords'); ?>:</label>
				<?php echo Form::textarea('comment_moderation_keys', Input::old('comment_moderation_keys', $meta['comment_moderation_keys'])); ?>
				<em><?php echo __('metadata.comment_moderation_keys_explain', 'Comma separated list of keywords.'); ?></em>
			</p>

			<p>
				<label><?php echo __('metadata.current_theme', 'Current theme'); ?>:</label>
				<select id="theme" name="theme">
					<?php foreach($themes as $theme => $about): ?>
					<?php $selected = (Input::old('theme', $meta['theme']) == $theme) ? ' selected' : ''; ?>
					<option value="<?php echo $theme; ?>"<?php echo $selected; ?>>
						<?php echo $about['name']; ?> by <?php echo $about['author']; ?>
					</option>
					<?php endforeach; ?>
				</select>

				<em><?php echo __('metadata.current_theme_explain', 'Your current theme.'); ?></em>
			</p>

			<p class="twitter">
				<label for="twitter"><?php echo __('metadata.twitter', 'Twitter'); ?>:</label>

				<span class="at">@</span>
				<input id="twitter" name="twitter" value="<?php echo Input::old('twitter', $meta['twitter']); ?>">

				<em><?php echo __('metadata.twitter_explain', 'Your twitter account.'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('metadata.save', 'Save changes'), array(
				'type' => 'submit',
				'class' => 'btn'
			)); ?>
		</aside>
	</form>

</section>

<?php echo $footer; ?>