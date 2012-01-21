<!-- <?php echo basename(__FILE__); ?> -->

<section class="content">
	<h1><?php echo $this->title(''); ?></h1>

	<?php if($this->get('metadata/description')): ?>
	<p><?php echo $this->get('metadata/description'); ?></p>
	<?php endif; ?>

	<ul class="items">
		<?php foreach($this->getPosts() as $key => $post): ?>
		<li>
		    <a href="/posts/<?php echo $post->slug; ?>" title="<?php echo $post->title; ?>">
		        <h2><?php echo $post->title; ?></h2>
		        <p><?php echo $post->description; ?></p>
		    </a>
		</li>
		<?php endforeach; ?>
	</ul>
</section>

