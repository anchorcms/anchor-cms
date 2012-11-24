<?php echo $header; ?>

<h1><?php echo __('extend.extend', 'Extend'); ?>
<a href="<?php echo admin_url('extend/fields/add'); ?>"><?php echo __('extend.create_field', 'Create a new field'); ?></a></h1>

<section class="content">
	<?php echo $messages; ?>

	<?php if(count($extend->results)): ?>
	<ul class="list">
		<?php foreach($extend->results as $field): ?>
		<li>
			<a href="<?php echo admin_url('extend/fields/edit/' . $field->id); ?>">
				<strong><?php echo $field->label; ?></strong>

				<span><?php echo $field->type . ' ' . $field->field; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $extend->links(); ?>
	<?php endif; ?>

	<aside class="sidebar">
		<div class="filter">
			<a href="<?php echo admin_url('extend/fields'); ?>">Custom Fields</a>
			<a href="<?php echo admin_url('extend/metadata'); ?>">Metadata</a>
			<a href="<?php echo admin_url('extend/plugins'); ?>">Plugins (Coming soon, yo!)</a>
		</div>
	</aside>

</section>

<?php echo $footer; ?>