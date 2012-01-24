<!-- <?php echo basename(__FILE__); ?> -->

<section class="content">
    <?php foreach($this->getContent() as $page): ?>
        <h1><?php echo $page->title; ?></h1>
        <?php echo $page->content; ?>
        
        <small>
            <p>This page was last modified 32 days ago.</p>
        </small>
    <?php endforeach; ?>
</section>
