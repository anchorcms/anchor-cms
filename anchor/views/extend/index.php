<?php echo $header; ?>

<h1><?php echo __('extend.extend', 'Extend'); ?>
<a href="<?php echo url('extend/add'); ?>"><?php echo __('extend.create_field', 'Create a new field'); ?></a></h1>

<?php echo $messages; ?>

<section class="content">
	<ul class="list">
		<?php foreach($extend->results as $field): ?>
		<li>
			<a href="<?php echo url('extend/edit/' . $field->id); ?>">
				<strong><?php echo $field->label; ?></strong>

				<span><?php echo $field->type . ' ' . $field->field; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $extend->links(); ?>
</section>

<?php echo $footer; ?>