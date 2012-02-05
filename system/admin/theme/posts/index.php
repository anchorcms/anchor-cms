<h1>Posts <a href="/admin/posts/add">Create a new post</a></h1>

<?php echo notifications(); ?>

<section class="content">
	
	<?php if(has_posts()): ?>
	<ul class="list">
	    <?php while(posts()): ?>
	    <li>
	        <a href="<?php echo URL_PATH; ?>admin/posts/edit/<?php echo post_id(); ?>">
	            <strong><?php echo post_title(); ?></strong>
	            <span>Created <time><?php echo post_date(); ?></time> by <?php echo post_author(); ?></span>
	            
	            <i class="status"><?php echo ucwords(post_status()); ?></i>
	        </a>
	    </li>
	    <?php endwhile; ?>
	</ul>
	<?php else: ?>
	<p>No posts just yet. Why not <a href="<?php echo URL_PATH; ?>admin/posts/add">write a new one</a>?</p>
	<?php endif; ?>
</section>