<?php if(has_posts()): ?>
    <ul class="items wrap">
    	<?php while(posts()): ?>
    	<li>
    		<a href="<?php echo post_url(); ?>" title="<?php echo post_title(); ?>">
    		    <time datetime="<?php echo date(DATE_W3C, post_time()); ?>"><?php echo relative_time(post_time()); ?></time>
    		    <h2><?php echo post_title(); ?></h2>
    		</a>
    	</li>
    	<?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Looks like you have some writing to do!</p>
<?php endif; ?>