<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('extend.variables'); ?></h1>

	<nav>
		<?php echo Html::link('admin/extend/variables/add', __('extend.create_variable'), array('class' => 'btn')); ?>
	</nav>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(count($variables)): ?>
	<ul class="list">
		<?php foreach($variables as $var): ?>
		<li>
			<a href="<?php echo Uri::to('admin/extend/variables/edit/' . $var->key); ?>">
				<strong><?php echo substr($var->key, strlen('custom_')); ?></strong>
				<p><?php echo e($var->value); ?></p>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p class="empty">
		<span class="icon"></span> <?php echo __('extend.novars_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>