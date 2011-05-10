<div id="frame">
	<ul>
		<li><strong>Post ID:</strong> <?php echo $post->id; ?></li>
		<li><strong>Post Title:</strong> <?php echo $post->title; ?></li>
	</ul>
</div>

<article id="article">
<?php
//	Then get the HTML, given to us in the admin panel
	echo $post->content;
?>
</article>