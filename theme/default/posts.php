<!-- <?php echo basename(__FILE__); ?> -->

<section class="content">
<?php foreach($this->getPosts() as $post): ?>

	<h1><?php echo $post->title; ?></h1>
	
	<?php echo $post->html; ?>

	<?php if($this->isCustom($post)): ?>
		The following is a custom post.
	<?php endif; ?>
	
	<!-- <?php var_dump($post); ?> -->
	
<?php endforeach; ?>
</section>

