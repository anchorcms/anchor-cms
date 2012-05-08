
<h1><?php echo __('plugins.plugins', 'Plugins'); ?></h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<h2><?php echo __('plugins.loaded_plugins', 'Loaded Plugins'); ?></h2>
		<?php
		$realhooks = array();
		foreach ($plugins as $type => $hooks): ?>
			<?php if (count($hooks) === 0) continue; ?>
			<h3><?php echo $type; ?></h3>
			<ul class="list">
			<?php
			foreach ($hooks as $name => $hook):
				$realhooks[$name] = $hook;
			?>
				<li><?php echo $name; ?> [/<?php
				echo str_replace(PATH, '', $names[$name]);
				?>]</li>	
			<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
	<?php if (count($realhooks) === 0): ?>
	<h3><?php echo __('plugins.no_plugins', 'No Loaded Plugins'); ?></h3>
	<?php endif; ?>
	<h2><?php echo __('plugins.files_found', 'Plugin Files Found'); ?></h2>
	<ul class="list">
	<?php foreach ($files as $file): ?>
		<li>/<?php echo str_replace(PATH, '', $file); ?></li>
	<?php endforeach; ?>
	</ul>
	<?php if (count($files) === 0): ?>
	<h3><?php echo __('plugins.no_files', 'No Plugin Files Found'); ?></h3>
	<?php endif; ?>
</section>