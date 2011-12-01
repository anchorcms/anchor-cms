<?php foreach($this->getPosts() as $post): ?>
	<?php if($this->isCustom($post)): ?>
		The following is a custom post.
	<?php endif; ?>
	
	<?php dump($post); ?>
<?php endforeach; ?>