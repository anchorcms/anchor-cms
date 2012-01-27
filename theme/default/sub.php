<section class="content">
    <?php foreach($this->getContent() as $page): ?>
        <h1><?php echo $page->title; ?></h1>
        
        <?php echo $page->content; ?>
    <?php endforeach; ?>
</section>