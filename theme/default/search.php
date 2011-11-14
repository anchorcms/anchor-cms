<?php
    include PATH . '/system/classes/search.php';
    $search = new Search($this->config);
?>

<section class="content">
    <h1><?php echo $this->title(); ?></h1>
    <ul>
    <?php foreach($search->getResults() as $result): ?>
        <li>
            <?php dump($result); ?>
            <a href="/<?php echo $result->slug; ?>" title="<?php echo $result-title; ?>">
                <h2><?php echo $result->title; ?></h2>
                <p><?php echo $this->shorten($result->content, 100); ?></p>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
</section>