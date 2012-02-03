
<?php if(count(posts())): ?>
<ul class="posts">
	<?php foreach(posts() as $post): ?>
	<li>
		<h3><?php echo $post->title; ?></h3>
		<p><?php echo $post->description; ?></p>
		<p><a class="btn" href="<?php echo $post->url; ?>" title="<?php echo $post->title; ?>">Read</a></p>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<section class="content">
	<p>Looks like you have some writing to do!</p>
</section>
<?php endif; ?>

