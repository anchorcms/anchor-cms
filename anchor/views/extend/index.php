<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.extend', 'Extend'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<li>
			<a href="<?php echo Uri::to('admin/extend/fields'); ?>">
				<strong>Fields</strong>

				<span>Create additional fields</span>
			</a>
		</li>
		<li>
			<a href="<?php echo Uri::to('admin/extend/variables'); ?>">
				<strong>Variables</strong>

				<span>Create additional metadata</span>
			</a>
		</li>
		<li>
			<a href="<?php echo Uri::to('admin/extend/metadata'); ?>">
				<strong>Metadata</strong>

				<span>Manage your site data</span>
			</a>
		</li>
		<li>
			<a href="<?php echo Uri::to('admin/extend/plugins'); ?>">
				<strong>Plugins</strong>

				<span>Coming soon, yo!</span>
			</a>
		</li>
	</ul>
</section>

<?php echo $footer; ?>