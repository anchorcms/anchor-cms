<!doctype html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <title>Upgrade Anchor CMS</title>
        <mate name="robots" content="noindex, nofollow">
        <link rel="stylesheet" href="assets/css/app.css">
    </head>
    <body>
    
    	<h1><img src="assets/img/logo.gif" alt="Anchor install logo"></h1>
    	
    	<?php
    	
    	$errors = array();
    	
    	if(file_exists('../config.php') === false) {
    		$errors[] = 'Please run the install';
    	} else {
			// note: on win the only way to really test is to try and write a new file to disk.
			if(@file_put_contents('../test.php', '<?php //test') === false) {
				$errors[] = 'It looks like the root directory is not writable, Please make the root directory writable until the upgrade is complete.';
			} else {
				unlink('../test.php');
			}
    	}
    	
    	?>

    	<?php if(count($errors)): ?>
    	
    	<div class="content">
    		<h2>Woops.</h2>
    		
    		<p>Looks like we've hit a problem.</p>
    		
    		<ul>
    			<?php foreach($errors as $error): ?>
    			<li><?php echo $error; ?></li>
    			<?php endforeach; ?>
    		</ul>
    		
    		<p><a href="index.php" class="button" style="float: none; display: inline-block;">Ok ive fixed these, start migration</a></p>
    	</div>
    	
    	<?php else: ?>

        <div class="content">
            <h2>Upgrading Anchor.</h2>

			<p>Thank you for downloading the latest version of Anchor. 
			To get you up and running we need to make a few changes to bring you up to date.</p>

            <form action="run.php" method="post">
                <button type="submit">Start migration</button>
            </form>
        </div>
        
        <?php endif; ?>
        
        <p class="footer">Made with love by <a href="//twitter.com/visualidiot">Visual Idiot</a>. 
        If it's not working, send him a message on Twitter. He'll reply.</p>
    </body>
</html>
