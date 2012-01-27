<?php foreach($this->getPosts() as $post): ?>
	
	<?php if($this->isCustom($post)) echo '<!-- This is a custom post. -->'; ?>
	
	<?php echo $post->html; ?>
	
<?php endforeach; ?>