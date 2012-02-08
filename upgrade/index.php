<!doctype html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <title>Upgrade Anchor CMS</title>
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
    	
    	// curl
    	if(function_exists('curl_init') === false) {
    		$compat[] = 'Anchor requires PHP cURL to be installed and enabled';
    	}
    	
    	// PDO
    	if(class_exists('PDO') === false) {
    		$compat[] = 'Anchor requires PDO (PHP Data Objects)';
    	} else {
    		if(in_array('mysql', PDO::getAvailableDrivers()) === false) {
    			$compat[] = 'Anchor requires the MySQL PDO Driver';
    		}
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
    		
    		<p><a href="." class="button" style="float: none; display: inline-block;">Ok, I've fixed these, run the upgrade.</a></p>
    	</div>
    
    	<?php elseif(file_exists('../config.php') === false): ?>
    	
    	<div class="content">
    		<h2>Woops.</h2>
    		
    		<p>Anchor is not installed.</p>
    		
    		<p><a href="../" class="button" style="float: none; display: inline-block;">Return to the main site.</a></p>
    	</div>
    	
    	<?php else: ?>

        <div class="content">
            <h2>Anchor Upgrade.</h2>

            <p>Upgrading from <strong>0.4</strong> to <strong>0.5</strong>. 
            You can find out more and this release at <a href="https://github.com/visualidiot/Anchor-CMS">Github</a></p>

            <p><a href="./run.php" class="button" style="float: none; display: inline-block;">Run the upgrade.</a></p>
        </div>
        
        <?php endif; ?>
        
        <p class="footer">Made with love by <a href="//twitter.com/visualidiot">Visual Idiot</a>. If it's not working, send him a message on Twitter. He'll reply.</p>
    </body>
</html>
