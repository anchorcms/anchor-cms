<ul id="list">
  <?php
  foreach ($posts as $post) {
  	echo '<li class="post_' . $post->id . '"><a href="' . $urlpath . $post->slug . '" title="' . $post->title . '">' . $post->title . '</a></li>';
  }
  ?>
</ul>