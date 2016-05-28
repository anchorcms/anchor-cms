<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.extend'); ?></h1>
</hgroup>

<section class="wrap">
	

	<ul class="list">
		<li>
			<a href="<?php echo Uri::to('admin/extend/pagetypes'); ?>">
				<i class="fa fa-3x fa-anchor pull-left wow " data-wow-delay=".3s" style="visibility: visible; animation-delay: 0.3s; animation-name: bounceIn;"> </i>
				<strong><?php echo __('extend.pagetypes'); ?></strong>
				<span><?php echo __('extend.pagetypes_desc'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo Uri::to('admin/extend/fields'); ?>">
				<strong><?php echo __('extend.fields'); ?></strong>
				<span><?php echo __('extend.fields_desc'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo Uri::to('admin/extend/variables'); ?>">
				<strong><?php echo __('extend.variables'); ?></strong>
				<span><?php echo __('extend.variables_desc'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo Uri::to('admin/extend/metadata'); ?>">
				<strong><?php echo __('metadata.metadata'); ?></strong>
				<span><?php echo __('metadata.metadata_desc'); ?></span>
			</a>
		</li>
	</ul>
</section>

<?php echo $footer; ?>
