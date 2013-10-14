<?php echo $header; ?>

<?php echo $nav; ?>

<hgroup class="wrap">
	<h1><?php echo $info['name']; ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<nav class="sidebar">
		<ul>
			<li><a href="#">Link</a></li>
		</ul>
	</nav>

	<div class="main">
		<p><?php echo $info['description']; ?></p>
		<p><code>Version <?php echo $info['version']; ?></code></p>

		<aside class="buttons">
		<?php if($plugin): ?>
			<?php echo Html::link($url, 'Uninstall', array('class' => 'btn red delete')); ?>
		<?php else: ?>
			<?php echo Html::link($url, 'Install', array('class' => 'btn')); ?>
		<?php endif; ?>
			<?php echo Html::link('admin/extend/plugins', 'Return to plugins', array('class' => 'btn blue')); ?>
		</aside>
	</div>
</section>

<?php echo $footer; ?>