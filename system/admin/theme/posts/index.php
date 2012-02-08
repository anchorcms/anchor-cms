<h1>Posts <a href="<?php echo base_url('admin/posts/add'); ?>">Create a new post</a></h1>

<?php echo notifications(); ?>

<section class="content">
	
	<?php if(has_posts()): ?>
	<ul class="list">
	    <?php while(posts()): ?>
	    <li>
	        <a href="<?php echo base_url('admin/posts/edit/' . article_id()); ?>">
	            <strong><?php echo truncate(article_title(), 4); ?></strong>
	            <span>Created <time><?php echo article_date(); ?></time> by <?php echo article_author(); ?></span>
	            
	            <i class="status"><?php echo ucwords(article_status()); ?></i>
	        </a>
	    </li>
	    <?php endwhile; ?>
	</ul>
	<?php else: ?>
	<p>No posts just yet. Why not <a href="<?php echo base_url('admin/posts/add'); ?>">write a new one</a>?</p>
	<?php endif; ?>
</section>
