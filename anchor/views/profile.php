
<section class="profile">
	<h5><?php echo __('global.profile'); ?></h5>

	<?php foreach($profile as $row): ?>
	<p><code><?php echo $row['sql']; ?></code></p>
	<?php endforeach; ?>

	<p><?php echo __('global.profile_memory_usage'); ?>
	<?php echo readable_size(memory_get_peak_usage(true)); ?></p>
</section>
