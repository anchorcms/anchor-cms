<section class="content">
    
    <h1>You searched for &ldquo;<?php echo search_term(); ?>&rdquo;.</h1>
    
    <?php if(count(search_results())): ?>
        <p>We found <?php echo count(search_results()); ?> <?php echo pluralise(search_results(), 'result'); ?> for &ldquo;<?php echo search_term(); ?>&rdquo;</p>
        <ul class="items wrap">
        	<?php foreach(search_results() as $post): ?>
        	<li>
        		<a href="<?php echo $post->url; ?>" title="<?php echo $post->title; ?>">
        		    <time datetime="<?php echo date(DATE_W3C, $post->created); ?>"><?php echo relative_time($post->created); ?></time>
        		    <h2><?php echo $post->title; ?></h2>
        		</a>
        	</li>
        	<?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Unfortunately, there's no results for &ldquo;<?php echo search_term(); ?>&rdquo;. Did you spell everything correctly?</p>
    <?php endif; ?>
    
</section>