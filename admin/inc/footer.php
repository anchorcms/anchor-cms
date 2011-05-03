<?php
//	Set some variables to check
	$pdo = defined('PDO::ATTR_DRIVER_NAME') ? 'Yes' : 'No';
	$php = (phpversion() >= 5) ? 'Yes' : 'No';

?>

		<div id="right">
			<h1>System Check</h1>
			
			<?php
				if(is_writable($path . '/uploads/')) { $upload = 'Yes'; }
				if(file_exists($path . '/themes/default/')) { $themes = 'Yes'; }
			?>
						
			<?php if(($pdo == 'Yes') && ($php == 'Yes') && ($upload == 'Yes') && ($themes == 'Yes')) { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/success.gif" /></p>
			<p>The first mate assures me your server is all ready to sail. All systems go.</p>
			<?php } else if($pdo == 'No') { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/failure.gif" /></p>
			<p>It seems you don't have PDO installed on your server. Contact your host about this.</p>			
			<?php } else if($php == 'No') { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/failure.gif" /></p>
			<p>You need to run PHP version 5 (or greater) for the best results. PHP 4 has not been tested.</p>			
			<?php } else if($upload != 'Yes') { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/failure.gif" /></p>
			<p>I can't write to the <code>uploads</code> directory!</p>			
			<?php } else if($themes != 'Yes') { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/failure.gif" /></p>
			<p>You may have accidentally deleted the default theme. Please <a href="http://anchorcms.com/latest/download/zip">download</a> and reinstall it. Thanks!</p>
			<?php } else if(latest_version() !== true) { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/failure.gif" /></p>
			<p>Either calling home is disabled, or your version is out of date.</p>						
			<?php } else { ?>
			<p class="result"><img src="<?php echo $urlpath; ?>core/img/failure.gif" /></p>
			<p>THE SKY IS FALLING, RUN!!1!</p>						
			<?php } ?>			
		</div>
	</div>
</body>