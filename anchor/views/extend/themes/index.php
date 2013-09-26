<?php echo $header; ?>

<?php echo $nav; ?>

<hgroup class="wrap">
	<h1><?php echo __('themes.themes'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(count($themes)): ?>
	<ul class="list">
		<?php foreach($themes as $theme): ?>
		<li>
			<a href="<?php echo Uri::to('admin/extend/themes/' . $theme['slug']); ?>">
				<strong><?php echo $theme['name']; ?></strong>
				<span><?php echo $theme['description']; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p class="empty">
		<span class="icon"></span> <?php echo __('themes.nothemes_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>