<?php echo $header; ?>

<h1><?php echo __('extend.extend', 'Extend'); ?></h1>

<section class="content">
	<?php echo $messages; ?>

	<aside class="sidebar">
		<a href="<?php echo admin_url('extend/fields'); ?>">Custom Fields</a>
		<a href="<?php echo admin_url('extend/metadata'); ?>">Metadata</a>
		<a href="<?php echo admin_url('extend/plugins'); ?>">Plugins (Coming soon, yo!)</a>
	</aside>
</section>

<?php echo $footer; ?>