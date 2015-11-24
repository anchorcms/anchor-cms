<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.pagetypes'); ?></h1>

	<nav>
		<?php echo Html::link('admin/extend/pagetypes/add', __('extend.create_pagetype'), array('class' => 'btn')); ?>
	</nav>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(count($pagetypes) >= 1): ?>
	<ul class="list">
		<?php foreach($pagetypes as $type): ?>
		<li>
			<a href="<?php echo Uri::to('admin/extend/pagetypes/edit/' . $type->key); ?>">
				<strong><?php echo e($type->value); ?></strong>
				<p><?php echo $type->key; ?></p>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p class="empty">
		<span class="icon"></span> <?php echo __('extend.notypes_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>
