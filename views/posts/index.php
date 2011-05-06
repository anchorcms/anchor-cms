<h1>Latest Posts <a href="<?php echo $urlpath; ?>admin/add">+ Add A Post</a></h1>
<ul id="list">
<?php
foreach ($posts as $post) {
	echo '<li><a href="' . $urlpath . 'admin/edit/' . $post->id . '" title="' . $post->title . '">' . $post->title . '<span>' . time_ago(strtotime($post->date)) . '</span><img src="' . $urlpath . '/core/img/edit_link.png" alt="Edit this post" /></a></li>';
}
?>
</ul>