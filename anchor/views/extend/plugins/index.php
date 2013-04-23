<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('plugins.plugins'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(count($plugins)): ?>
	<ul class="list">
		<?php foreach($plugins as $plugin): ?>
		<li>
			<a href="<?php echo Uri::to('admin/extend/plugins/' . $plugin['path']); ?>">
				<strong><?php echo $plugin['name']; ?></strong>
				<span><?php echo $plugin['description']; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p class="empty">
		<span class="icon"></span> <?php echo __('plugins.noplugins_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>