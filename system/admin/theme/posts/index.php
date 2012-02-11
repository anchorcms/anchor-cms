<h1>Posts <a href="<?php echo admin_url('posts/add'); ?>">Create a new post</a></h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<?php if($posts->length()): ?>
	<ul class="list">
	    <?php foreach($posts as $article): ?>
	    <li>
	        <a href="<?php echo admin_url('posts/edit/' . $article->id); ?>">
	            <strong><?php echo truncate($article->title, 4); ?></strong>
	            <span>Created <time><?php echo date(Config::get('metadata.date_format'), $article->created); ?></time> 
	            by <?php echo $article->author; ?></span>
	            
	            <i class="status"><?php echo $article->status; ?></i>
	        </a>
	    </li>
	    <?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p>No posts just yet. Why not <a href="<?php echo admin_url('posts/add'); ?>">write a new one</a>?</p>
	<?php endif; ?>
</section>
