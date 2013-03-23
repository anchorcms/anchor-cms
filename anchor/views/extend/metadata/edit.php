<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('metadata.metadata'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/extend/metadata'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('metadata.sitename'); ?>:</label>
				<?php echo Form::text('sitename', Input::previous('sitename', $meta['sitename'])); ?>
				<em><?php echo __('metadata.sitename_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('metadata.sitedescription'); ?>:</label>
				<?php echo Form::textarea('description', Input::previous('description', $meta['description'])); ?>
				<em><?php echo __('metadata.sitedescription_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('metadata.homepage'); ?>:</label>
				<?php echo Form::select('home_page', $pages, Input::previous('home_page', $meta['home_page'])); ?>
				<em><?php echo __('metadata.homepage_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('metadata.postspage'); ?>:</label>
				<?php echo Form::select('posts_page', $pages, Input::previous('posts_page', $meta['posts_page'])); ?>
				<em><?php echo __('metadata.postspage_explain'); ?></em>
			</p>
			<p>
				<label for="posts_per_page"><?php echo __('metadata.posts_per_page'); ?>:</label>
				<?php echo Form::input('range', 'posts_per_page', Input::previous('posts_per_page', $meta['posts_per_page']),
					array('min' => 1, 'max' => 15)); ?>
				<em><?php echo __('metadata.posts_per_page_explain'); ?></em>
			</p>
		</fieldset>

		<fieldset class="split">
			<legend><?php echo __('metadata.comment_settings'); ?></legend>
			<p>
				<label for="auto_published_comments"><?php echo __('metadata.auto_publish_comments'); ?>:</label>
				<?php $checked = Input::previous('auto_published_comments', $meta['auto_published_comments']) ? ' checked' : ''; ?>
				<input id="auto_published_comments" name="auto_published_comments" type="checkbox" value="1"<?php echo $checked; ?>>
				<em><?php echo __('metadata.auto_publish_comments_explain'); ?></em>
			</p>
			<p>
				<label for="comment_notifications"><?php echo __('metadata.comment_notifications'); ?>:</label>
				<?php $checked = Input::previous('comment_notifications', $meta['comment_notifications']) ? ' checked' : ''; ?>
				<input id="comment_notifications" name="comment_notifications" type="checkbox" value="1"<?php echo $checked; ?>>
				<em><?php echo __('metadata.comment_notifications_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('metadata.comment_moderation_keys'); ?>:</label>
				<?php echo Form::textarea('comment_moderation_keys',
					Input::previous('comment_moderation_keys', $meta['comment_moderation_keys'])); ?>
				<em><?php echo __('metadata.comment_moderation_keys_explain'); ?></em>
			</p>
		</fieldset>

		<fieldset class="split">
			<legend><?php echo __('metadata.theme_settings'); ?></legend>
			<p>
				<label for="theme"><?php echo __('metadata.current_theme'); ?>:</label>
				<select id="theme" name="theme">
					<?php foreach($themes as $theme => $about): ?>
					<?php $selected = (Input::previous('theme', $meta['theme']) == $theme) ? ' selected' : ''; ?>
					<option value="<?php echo $theme; ?>"<?php echo $selected; ?>>
						<?php echo $about['name']; ?> by <?php echo $about['author']; ?>
					</option>
					<?php endforeach; ?>
				</select>

				<em><?php echo __('metadata.current_theme_explain', 'Your current theme.'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>