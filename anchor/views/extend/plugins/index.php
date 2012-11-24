<?php echo $header; ?>

<h1><?php echo __('extend.plugins', 'Plugins'); ?></h1>

<section class="content">
	<?php echo $messages; ?>

	<p class="empty">
		<span class="icon"></span>Coming soon, Yo!
	</p>

	<aside class="sidebar">
		<div class="filter">
			<a href="<?php echo admin_url('extend/fields'); ?>">Custom Fields</a>
			<a href="<?php echo admin_url('extend/metadata'); ?>">Metadata</a>
			<a href="<?php echo admin_url('extend/plugins'); ?>">Plugins (Coming soon, yo!)</a>
		</div>
	</aside>
</section>

<?php echo $footer; ?>