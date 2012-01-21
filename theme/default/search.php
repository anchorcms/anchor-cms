<!-- <?php echo basename(__FILE__); ?> -->

<section class="content">
    <h1><?php echo $this->title(); ?></h1>
    
    <?php if(Search::getResults()): ?>
    <ul>
    <?php foreach(Search::getResults() as $result): ?>
        <li>
            <a href="/<?php echo $result->slug; ?>" title="<?php echo $result-title; ?>">
                <h2><?php echo $result->title; ?></h2>
                <p><?php echo $this->shorten($result->content, 100); ?></p>
                <small>/<?php echo $result->slug; ?></small>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php else: ?>
	    <p>Unfortunately, there's no results for &ldquo;<?php echo Search::value('term'); ?>&rdquo;.</p>
    <?php endif; ?>
</section>
