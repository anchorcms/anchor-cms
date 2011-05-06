<div id="frame">
	<ul>
		<li><strong>Post ID:</strong> <?php $post->get_id(); ?></li>
		<li><strong>Post Title:</strong> <?php $post->get_title(); ?></li>
	</ul>
</div>

<article id="article">
<?php
//	Then get the HTML, given to us in the admin panel
	$post->get_content();
?>
</article>