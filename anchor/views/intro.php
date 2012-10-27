<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Welcome to Anchor</title>
        
        <style>
            body {
                font: 100% "Helvetica Neue", sans-serif;
                text-align: center;
                
                background: #444f5f;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <h1>Welcome to Anchor. Let’s go.</h1>
        <a href="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/install'; ?>">Run the installer</a>
    </body>
</html>