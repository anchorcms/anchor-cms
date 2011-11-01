<!doctype html>
<html lang="en">
    <head>
        <title><?php echo $this->title(); ?></title>
        <meta charset="utf-8">
        
        <link rel="stylesheet" href="<?php echo PATH; ?>theme/<?php echo $this->get('theme'); ?>/reset.css">
        <link rel="stylesheet" href="<?php echo PATH; ?>theme/<?php echo $this->get('theme'); ?>/style.css">
        
        <script src="//code.jquery.com/jquery-latest.min.js"></script>
        <script src="<?php echo PATH; ?>theme/<?php echo $this->get('theme'); ?>/site.js"></script>
    </head>
    <body>
        <h1><?php echo $this->title(); ?></h1>
        <ul>
        <?php foreach($this->getPosts() as $key => $post): ?>
            <li>
                <a href="/posts/<?php echo $post->slug; ?>" title="<?php echo $post->title; ?>">
                    <h2><?php echo $post->title; ?></h2>
                    <p><?php echo $post->description; ?></p>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    </body>
</html>