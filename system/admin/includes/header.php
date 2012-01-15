<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Anchor CMS &middot; Documentation</title>
    
    <link rel="stylesheet" href="<?php echo $this->get('base_path'); ?>system/admin/theme/css/admin.css">
</head>
<body>

    <header id="top">
        <a id="logo" href="<?php echo $this->get('base_path'); ?>admin">
            <img src="<?php echo $this->get('base_path'); ?>system/admin/theme/img/logo.png" alt="Anchor CMS">
        </a>
        
        <?php if(User::current() !== false): ?>
        <nav>
            <ul>
                <?php foreach(array('Posts' => 'admin', 'Users' => 'admin/users', 'Metadata' => 'admin/metadata') as $title => $link): ?>
                <li class="<?php echo $_SERVER['REQUEST_URI'] === $this->get('base_path') . $link ? 'active' : ''; ?>">
                    <a href="<?php echo $this->get('base_path') . $link; ?>"><?php echo $title; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        
        <a class="logout" href="<?php echo $this->get('base_path'); ?>admin/logout">Log out</a>
        <?php endif; ?>
    </header>
    
    <h1><?php include_once 'titles.php'; echo $titles[$this->url[1] . '/' . $this->url[2]]; ?></h1>