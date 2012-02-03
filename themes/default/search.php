
<?php if(count(search_results())): ?>
<p>We found <?php echo count(search_results()); ?> result<?php if(count(search_results()) > 1) echo 's'; ?> for &ldquo;<?php echo search_term(); ?>&rdquo;.</p>
<ul class="items">
	<?php foreach(search_results() as $post): ?>
	<li>
		<a href="<?php echo $post->url; ?>" title="<?php echo $post->title; ?>">
		    <h2><?php echo $post->title; ?></h2>
		    <p><?php echo $post->description; ?></p>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<p>Unfortunately, there's no results for &ldquo;<?php echo search_term(); ?>&rdquo;.</p>
<?php endif; ?>

