
<section class="profile">
	<h5>Profile</h5>

	<?php foreach($profile as $row): extract($row); ?>
	<pre><code><?php echo $sql; ?></code></pre>
	<?php endforeach; ?>
</section>
