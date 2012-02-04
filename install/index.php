<?php if(file_exists('../config.php')) die('Anchor is already installed. <b>You should really delete this folder!</b>'); ?>
<!doctype html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <title>Install Anchor CMS</title>
        
        <link rel="stylesheet" href="/install/css/install.css">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/install/js/jquery.js"><\/script>');</script>
        <script src="/install/js/install.js"></script>
    </head>
    
    <body>
    
        <p class="nojs error">You will need Javascript enabled for this installation. <em>Sorry :(</em></p>
    
        <h1><img src="/install/img/logo.gif" alt="Anchor install logo"></h1>
        
        <div class="content">
            <h2>Welcome to Anchor.</h2>

            <p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve 
            found the right place. Simply fill in the details below, and you&rsquo;ll have your 
            new blog set up in no time.</p>
            
            <small>If you want a more custom install, feel free to edit <code>config.default.php</code> 
            (before or after this installation, it doesn't really matter, as long as you rename it to 
            <code>config.php</code>).</small>
            
            <div class="notes">
            	<?php if(floatval(PHP_VERSION) < 5.3): ?>
				<p class="error">Anchor requires PHP 5.3 or newer, your current environment is running PHP <?php echo floatval(PHP_VERSION); ?></p>
				<?php endif; ?>
            </div>
            
            <form method="get" novalidate>
                <fieldset id="diagnose">
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
                    
                    <a href="#check" class="button">Check database details</a>
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
                            
                            foreach(glob('../themes/*') as $theme) {
                                $name = str_replace('../themes/', '', $theme);
                                
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
