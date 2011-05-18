<?php function time_ago($a) { }; ?>
<h1>Latest Posts <a href="<?php echo $urlpath; ?>admin/posts/new">+ Add A Post</a></h1>
<ul id="list">
<?php
foreach ($posts as $post) {
	echo '<li><a href="' . $urlpath . 'admin/posts/edit/' . $post->id . '" title="' . $post->title . '">' . $post->title . '<span>' . time_ago(strtotime($post->date)) . '</span><img src="' . $urlpath . 'core/img/edit_link.png" alt="Edit this post" /></a></li>';
}
?>
</ul>