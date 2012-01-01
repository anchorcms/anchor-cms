<?php if(file_exists('../config.php')) die('You should really delete this folder now Anchor is installed.'); ?>
<!doctype html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <title>Install Anchor CMS</title>
        
        <link rel="stylesheet" href="install.css">
        
        <script src="../theme/default/js/jquery.js"></script>
        <script src="install.js"></script>
    </head>
    
    <body>
    
        <p id="error"><span>You will need Javascript enabled for this installation. <em>Sorry :(</em></span></p>
    
        <h1><img src="files/logo.gif" alt="Anchor install logo"></h1>
        
        <div>
            <h2>Welcome to Anchor.</h2>
            <p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve found the right place. Simply fill in the details below, and you&rsquo;ll have your new blog set up in no time.</p>
            <small>If you want a more custom install, feel free to edit <code>config.default.php</code> (before or after this installation, it doesn't really matter, as long as you rename it to <code>config.php</code>).</small>
            
            <form method="get" novalidate>
                <fieldset>
                    <legend>Your database details</legend>
                    
                    <p>
                        <label for="host">Your database host:</label>
                        <input id="host" type="url" name="host" placeholder="localhost">
                    </p>
                    <p>
                        <label for="user">Your database username:</label>
                        <input id="user" autocapitalize="off" name="user" placeholder="root">
                    </p>
                    <p>
                        <label for="pass">Your database password:</label>
                        <input id="pass" autocapitalize="off" name="pass" placeholder="password">
                    </p>
                    <p>
                        <label for="db">Your database name:</label>
                        <input id="db" autocapitalize="off" name="db" placeholder="anchor">
                    </p>
                    
                    <a href="javascript:''" class="button">Check database details</a>
                </fieldset>
                
                <fieldset>
                    <legend>About your site</legend>
                    
                    <p>
                        <label for="name">Site name:</label>
                        <input id="name" name="name" placeholder="My awesome Anchor site">
                    </p>
                    
                    <p>
                        <label for="description">Site description:</label>
                        <textarea id="description" name="description"></textarea>
                    </p>
                    
                    <p>
                        <label for="theme">Theme:</label>
                        <select id="theme" name="theme">
                            <?php
                            
                            foreach(glob('../theme/*') as $theme) {
                                $name = str_replace('../theme/', '', $theme);
                                
                                if(file_exists($theme . '/about.txt')) {
                                    echo '<option value="' . $name . '">' . $name . '</option>';
                                }
                            }
                            
                            ?>
                        </select>
                    </p>
                    
                </fieldset>
                
                <br style="clear: both;">
                <button type="submit">Install Anchor</button>
            </form>
        </div>
        
        <p class="footer">Made with love by <a href="//twitter.com/visualidiot">Visual Idiot</a>. If it's not working, send him a message on Twitter. He'll reply.</p>
    </body>
</html>