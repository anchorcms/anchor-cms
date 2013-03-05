
<section class="profile">
	<h5>Profile</h5>

	<?php foreach($profile as $row): ?>
	<p><code><?php echo $row['sql']; ?></code></p>
	<?php endforeach; ?>

	<p>Total memory usage <?php echo readable_size(memory_get_peak_usage(true)); ?></p>
</section>
