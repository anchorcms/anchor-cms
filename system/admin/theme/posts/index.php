
<h1><?php echo __('posts.posts', 'Posts'); ?> 
<a href="<?php echo admin_url('posts/add'); ?>"><?php echo __('posts.create_post', 'Create a new post'); ?></a></h1>

<?php echo Notifications::read(); ?>

<section class="content">
	<?php if($posts->length()): ?>
	<ul class="list">
	    <?php foreach($posts as $article): ?>
	    <li>
	        <a href="<?php echo admin_url('posts/edit/' . $article->id); ?>">
	            <strong><?php echo truncate($article->title, 4); ?></strong>
	            <span><?php echo __('posts.created', 'Created'); ?> <time><?php echo date(Config::get('metadata.date_format'), $article->created); ?></time> 
	            <?php echo __('posts.by', 'by'); ?> <?php echo $article->author; ?></span>
	            
	            <i class="status"><?php echo $article->status; ?></i>
	        </a>
	    </li>
	    <?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p><a href="<?php echo admin_url('posts/add'); ?>"><?php echo __('posts.noposts', 'No posts just yet. Why not write a new one?'); ?></a></p>
	<?php endif; ?>
</section>
