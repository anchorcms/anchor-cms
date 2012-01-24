<!-- <?php echo basename(__FILE__); ?> -->

<section class="content">
	<h1><?php echo $this->title(''); ?></h1>

	<?php if($this->get('metadata/description')): ?>
	<p><?php echo $this->get('metadata/description'); ?></p>
	<?php endif; ?>

	<ul class="items">
		<?php foreach($this->getPosts() as $key => $post): ?>
		<li>
	        <h2><a href="/posts/<?php echo $post->slug; ?>" title="<?php echo $post->title; ?>"><?php echo $post->title; ?></a></h2>
	        <p>Posted on <?php echo date("jS M, Y", $post->date); ?> by <?php echo $post->author->real_name; ?></p>
	        <p><?php echo $post->description; ?></p>
	        <p><a href="/posts/<?php echo $post->slug; ?>">Read Me</a></p>
		</li>
		<?php endforeach; ?>
	</ul>
</section>

