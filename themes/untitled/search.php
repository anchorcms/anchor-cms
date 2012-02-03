
<?php if(count(search_results())): ?>
<section class="content">
	<p>We found <?php echo count(search_results()); ?> result<?php if(count(search_results()) > 1) echo 's'; ?> 
	for &ldquo;<?php echo search_term(); ?>&rdquo;.</p>
</section>
<ul class="posts">
	<?php foreach(search_results() as $post): ?>
	<li>
		<h3><?php echo $post->title; ?></h3>
		<p><?php echo $post->description; ?></p>
		
		<?php if(user_authed()): ?>
		<p><a  class="quiet" href="/admin/posts/edit/<?php echo $post->id; ?>">Edit this article</a></p>
		<?php endif; ?>
		
		<p><a class="btn" href="<?php echo $post->url; ?>" title="<?php echo $post->title; ?>">Continue Reading</a></p>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<section class="content">
	<p>Unfortunately, there's no results for &ldquo;<?php echo search_term(); ?>&rdquo;.</p>
</section>
<?php endif; ?>

