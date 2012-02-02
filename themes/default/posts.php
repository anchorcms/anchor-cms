
<?php if(count(posts())): ?>
<ul class="items">
	<?php foreach(posts() as $post): ?>
	<li>
		<a href="<?php echo $post->url; ?>" title="<?php echo $post->title; ?>">
		    <h2><?php echo $post->title; ?></h2>
		    <p><?php echo $post->description; ?></p>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<p>Looks like you have some writing to do!</p>
<?php endif; ?>

