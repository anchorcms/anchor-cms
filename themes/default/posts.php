<?php if(count(posts())): ?>
    <ul class="items wrap">
    	<?php foreach(posts() as $post): ?>
    	<li>
    		<a href="<?php echo $post->url; ?>" title="<?php echo $post->title; ?>">
    		    <time datetime="<?php echo date(DATE_W3C, $post->created); ?>"><?php echo relative_time($post->created); ?></time>
    		    <h2><?php echo $post->title; ?></h2>
    		</a>
    	</li>
    	<?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Looks like you have some writing to do!</p>
<?php endif; ?>