
<h1><?php echo __('posts.posts', 'Posts'); ?> 
<a href="<?php echo admin_url('posts/add'); ?>"><?php echo __('posts.create_post', 'Create a new post'); ?></a></h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<?php if($posts->length()): ?>
	<ul class="list">
	    <?php foreach($posts as $article): ?>
	    <li>
	        <a href="<?php echo admin_url('posts/edit/' . $article->id); ?>">
	            <strong><?php echo $article->title; ?></strong>
	            <span>
	                <time><?php echo date(Config::get('metadata.date_format'), $article->created); ?></time> 
	                <?php echo $article->author; ?>
	                
    	            <i title="This post is currently <?php echo $article->status; ?>" class="status <?php echo strtolower($article->status); ?>"><?php echo $article->status; ?></i>
	            </span>
	            
	            <p><?php echo strip_tags(truncate($article->description ? $article->description : $article->html, 40)); ?></p>
	        </a>
	    </li>
	    <?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p><a href="<?php echo admin_url('posts/add'); ?>"><?php echo __('posts.noposts', 'No posts just yet. Why not write a new one?'); ?></a></p>
	<?php endif; ?>
</section>
