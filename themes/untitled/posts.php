
<?php if(count(posts())): ?>
<ul class="posts">
	<?php foreach(posts() as $post): ?>
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
	<p>Looks like you have some writing to do!</p>
</section>
<?php endif; ?>

