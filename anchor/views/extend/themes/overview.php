<?php echo $header; ?>

<?php echo $nav; ?>

<hgroup class="wrap">
	<h1><?php echo __('themes.themes'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(!empty($theme)): ?>
	<ul class="list">
		<li>
			<a href="<?php echo Uri::to('admin/extend/themes/' . $theme['slug']); ?>">
				<strong><?php echo $theme['name']; ?></strong>
				<span><?php echo $theme['description']; ?></span>
			</a>
			<p>Made by <a target="_blank" href="<?php echo $theme['site']; ?>"><?php echo $theme['author']; ?></a> | <a target="_blank" href="<?php echo $theme['license']; ?>">License</a></p>
		</li>
	</ul>
	<?php else: ?>
	<p class="empty">
		<span class="icon"></span> <?php echo __('themes.notheme_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>