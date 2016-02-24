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
				<label for="label-sitename"><?php echo __('metadata.sitename'); ?>:</label>
				<?php echo Form::text('sitename', Input::previous('sitename', $meta['sitename']), array('id' => 'label-sitename')); ?>
				<em><?php echo __('metadata.sitename_explain'); ?></em>
			</p>
			<p>
				<label for="label-sitedescription"><?php echo __('metadata.sitedescription'); ?>:</label>
				<?php echo Form::textarea('description', Input::previous('description', $meta['description']), array('id' => 'label-sitedescription')); ?>
				<em><?php echo __('metadata.sitedescription_explain'); ?></em>
			</p>
			<p>
				<label for="label-homepage"><?php echo __('metadata.homepage'); ?>:</label>
				<?php echo Form::select('home_page', $pages, Input::previous('home_page', $meta['home_page']), array('id' => 'label-homepage')); ?>
				<em><?php echo __('metadata.homepage_explain'); ?></em>
			</p>
			<p>
				<label for="label-postspage"><?php echo __('metadata.postspage'); ?>:</label>
				<?php echo Form::select('posts_page', $pages, Input::previous('posts_page', $meta['posts_page']), array('id' => 'label-postspage')); ?>
				<em><?php echo __('metadata.postspage_explain'); ?></em>
			</p>
			<p>
				<label for="label-posts_per_page"><?php echo __('metadata.posts_per_page'); ?>:</label>
				<?php echo Form::input('range', 'posts_per_page', Input::previous('posts_per_page', $meta['posts_per_page']),
					array('min' => 1, 'max' => 15, 'id' => 'label-posts_per_page')); ?>
				<em><?php echo __('metadata.posts_per_page_explain'); ?></em>
				<em class="visible" id="posts_per_page_number"><?php echo $meta['posts_per_page']; ?></em>
			</p>
			<p>
				<label for="label-all_posts"><?php echo __('metadata.show_all_posts'); ?>:</label>
				<?php $checked = Input::previous('show_all_posts', $meta['show_all_posts']) ? ' checked' : ''; ?>
				<?php echo Form::checkbox('show_all_posts', 1, $checked, array('id' => 'label-show_all_posts')); ?>
				<em><?php echo __('metadata.show_all_posts_explain'); ?></em>
			</p>
		</fieldset>

		<fieldset class="split">
			<legend><?php echo __('metadata.comment_settings'); ?></legend>
			<p>
				<label for="label-auto_published_comments"><?php echo __('metadata.auto_publish_comments'); ?>:</label>
				<?php $checked = Input::previous('auto_published_comments', $meta['auto_published_comments']) ? ' checked' : ''; ?>
				<?php echo Form::checkbox('auto_published_comments', 1, $checked, array('id' => 'label-auto_published_comments')); ?>
				<em><?php echo __('metadata.auto_publish_comments_explain'); ?></em>
			</p>
			<p>
				<label for="label-comment_notifications"><?php echo __('metadata.comment_notifications'); ?>:</label>
				<?php $checked = Input::previous('comment_notifications', $meta['comment_notifications']) ? ' checked' : ''; ?>
				<?php echo Form::checkbox('comment_notifications', 1, $checked, array('id' => 'label-comment_notifications')); ?>
				<em><?php echo __('metadata.comment_notifications_explain'); ?></em>
			</p>
			<p>
				<label for="label-comment_moderation_keys"><?php echo __('metadata.comment_moderation_keys'); ?>:</label>
				<?php echo Form::textarea('comment_moderation_keys', Input::previous('comment_moderation_keys', $meta['comment_moderation_keys']), array('id' => 'label-comment_moderation_keys')); ?>
				<em><?php echo __('metadata.comment_moderation_keys_explain'); ?></em>
			</p>
		</fieldset>

		<fieldset class="split">
			<legend><?php echo __('metadata.theme_settings'); ?></legend>
			<p>
				<label for="label-theme"><?php echo __('metadata.current_theme'); ?>:</label>
				<select id="label-theme" name="theme">
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

			<?php echo Html::link('admin/extend' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>
		</aside>
	</form>
</section>

<script type="text/javascript">

	// Show posts per page count
	$(document).ready(function(){
		$('input[name="posts_per_page"]').change(function(){
			$('#posts_per_page_number').text($(this).val());
		});
	});

</script>
<?php echo $footer; ?>
