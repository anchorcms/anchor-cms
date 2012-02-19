<!doctype html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <title>Install Anchor CMS</title>
        <mate name="robots" content="noindex, nofollow">
        
        <link rel="stylesheet" href="assets/css/app.css">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/js/jquery.js"><\/script>');</script>
        <script src="assets/js/app.js"></script>
    </head>
    <body>
    
    	<h1><img src="assets/img/logo.gif" alt="Anchor install logo"></h1>
    	
    	<?php
    	
    	/*
    		Compatibility checks
    	*/
    	$compat = array();
    	
    	// php
    	if(version_compare(PHP_VERSION, '5.3.0', '<')) {
    		$compat[] = 'Anchor requires PHP 5.3 or newer.<br><em>Your current environment is running PHP ' . PHP_VERSION . '</em>';
    	}
    	
    	// PDO
    	if(class_exists('PDO') === false) {
    		$compat[] = 'Anchor requires PDO (PHP Data Objects)<br>
    		<em>You can find more about <a href="//php.net/manual/en/book.pdo.php">installing and setting up PHP Data Objects</a> 
    		on the php.net website</em>';
    	} else {
    		if(in_array('mysql', PDO::getAvailableDrivers()) === false) {
    			$compat[] = 'Anchor requires the MySQL PDO Driver<br>
    				<em>You can find more about <a href="//php.net/manual/en/ref.pdo-mysql.php">installing and setting up MySQL PDO Driver</a> 
    				on the php.net website</em>';
    		}
    	}

        // can we write a config file?
        // note: on win the only way to really test is to try and write a new file to disk.
        if(@file_put_contents('../test.php', '<?php //test') === false) {
            $compat[] = 'It looks like the root directory is not writable, we may not be able to automatically create your config.php file. 
                Please make the root directory writable until the installation is complete.';
        } else {
            unlink('../test.php');
        }

    	?>
    	
    	<?php if(count($compat)): ?>
    
    	<div class="content">
    		<h2>Woops.</h2>
    		
    		<p>Anchor is missing some requirements:</p>
    		
    		<ul style="padding-bottom: 1em;">
    			<?php foreach($compat as $item): ?>
    			<li><?php echo $item; ?></li>
    			<?php endforeach; ?>
    		</ul>
    		
    		<p><a href="." class="button" style="float: none; display: inline-block;">Ok, I've fixed these, run the installer.</a></p>
    	</div>
    
    	<?php elseif(file_exists('../config.php')): ?>
    	
    	<div class="content">
    		<h2>Woops.</h2>
    		
    		<p>Anchor is already installed. You should really delete this folder!</p>
    		
    		<p><a href="../" class="button" style="float: none; display: inline-block;">Return to the main site.</a></p>
    	</div>
    	
    	<?php else: ?>
    
        <p class="nojs error">You will need Javascript enabled for this installation. <em>Sorry :(</em></p>

        <div class="content">
            <h2>Welcome to Anchor.</h2>

            <p>If you were looking for a truly lightweight blogging experience, you&rsquo;ve 
            found the right place. Simply fill in the details below, and you&rsquo;ll have your 
            new blog set up in no time.</p>
            
            <small>If you want a more custom install, feel free to edit <code>config.default.php</code> 
            (before or after this installation, it doesn't really matter, as long as you rename it to 
            <code>config.php</code>).</small>
            
            <div class="notes"></div>
            
            <form method="get" novalidate>
                <fieldset id="diagnose">
                    <legend>Your database details</legend>
                    
                    <p>
                        <label for="host">Your database host:</label>
                        <input id="host" name="host" value="localhost">
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
                            <?php foreach(glob('../themes/*') as $theme): $name = basename($theme); ?>
                            <?php if(file_exists($theme . '/about.txt')): ?>
                            <option value="<?php echo $name; ?>" <?php if($name === 'default') echo 'selected'; ?>><?php echo ucwords($name); ?></option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    
                    <p>
                        <label for="email">Your Email Address:</label>
                        <input id="email" name="email">
                    </p>
                    
                    <p>
                        <label for="path">Site path:</label>
                        <input id="path" name="path" value="<?php echo dirname(dirname($_SERVER['SCRIPT_NAME'])); ?>">
                    </p>
                    
                    <p>
                        <label><input type="checkbox" name="clean_urls" value="1">
                        Use clean urls</label> (Apache mod_rewrite is enabled)
                    </p>
                    
                </fieldset>
                
                <br style="clear: both;">
                <button type="submit">Install Anchor</button>
            </form>
        </div>
        
        <?php endif; ?>
        
        <p class="footer">Made with love by <a href="//twitter.com/visualidiot">Visual Idiot</a>. 
        If it's not working, send him a message on Twitter. He'll reply.</p>
    </body>
</html>
