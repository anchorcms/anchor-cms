<?php

	function is_checked($post, $var) {
		if(isset($_POST['submit'])) {
			if($_POST[$post] == 'on') {
				return true;
			} else {
				return false;
			}
		} else {
			if($var === true) {
				return true;
			} else {
				return false;
			}
		}
	}
		
//	Return the path of the main directory
	$path = substr(dirname(__FILE__), 0, -5);
	
//	Get the URL path (from http://site.com/ onwards)
//	__DIR__ - $_SERVER['DOCUMENT_ROOT']
	$urlpath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

	include($path . '/core/connect.php');
	include($path . '/core/themes.php');
	include($path . '/core/stats.php');
	include($path . '/config/settings.php');
	
	$title = 'Site Settings';

	include('inc/header.php');
	
	if(isset($_POST['clean']) && $_POST['clean'] == 'on') { $url = 'true'; } else { $url = 'false'; }
	if(isset($_POST['updates']) && $_POST['updates'] == 'on') { $et = 'true'; } else { $et = 'false'; }
	
	if(isset($_POST['submit'])) {
		$update = file_put_contents($path . '/config/settings.php', '<?php

/******************************************************
 *
 *		General settings
 *
 ******************************************************
 *
 *		Anchor saves your site\'s settings in this
 *		file, so you can edit them here, or in the
 *		admin panel.
 */
 
//	Site name                  What\'s your blog called?
	$sitename = \'' . $_POST['sitename'] .'\';

//	Current theme        The name of the theme\'s folder
	$theme = \'' . $_POST['theme'] .'\';
	
//	Clean URLs	   Can your server support mod_rewrite?
	$clean_urls = ' . $url . ';
	
//	Calling home      Do you want to check for updates?
	$callhome = ' . $et . ';

?>');

		if($update) {
			$error = 'Settings saved.';
		} else {
			$error = 'Could not save settings. Try manually saving them in <code>config/settings.php</code>.';
		}
	}

?>

	<ul id="nav">
		<li><a href="<?php echo $urlpath; ?>admin/add">Add A Post</a></li>
		<li><a href="<?php echo $urlpath; ?>admin">Edit Posts</a></li>
		<li class="active"><a href="<?php echo $urlpath; ?>admin/metadata">Site Information</a></li>
		<li><a href="<?php echo $urlpath; ?>admin/users">Users</a></li>
		
		<a class="visit" href="<?php echo $urlpath; ?>">Visit Site <span style="padding-left: 12px; font-size: 10px; -webkit-font-smoothing: none;">&rarr;</span></a>
	</ul>

	<div id="content">
		<div id="left">
			<h1>Site Metadata</h1>
			
			<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
			
			<form action="" method="post" enctype="multipart/form-data">
				<p>
					<label for="sitename">Site name:</label>
					<input id="sitename" name="sitename" value="<?php if(isset($_POST['submit'])) { echo $_POST['sitename']; } else { echo $sitename; } ?>" />
				</p>
				<p>
					<label for="theme">Current theme:</label>
						<select name="theme">
						<?php
						if($dh = opendir($path . '/themes')) {
					        while(($file = readdir($dh)) !== false) {
					            if(($file != '.') && ($file != '..')) {
					            	$title = str_replace('_', ' ', $file);
					            	$title = ucwords($title);
					            	if($file == 'default') { $default = ' selected'; }
					            	echo '<option value="' . strtolower($file) . '"'. $default .'>' . $title . '</option>';
					            }
					        }
					        closedir($dh);
					    }
						?>
						</select>
				</p>
				<p>
					<label for="clean">Use Clean URLs?</label>
					<input id="clean" name="clean" type="checkbox" <?php if(is_checked('clean', $clean_urls)) { ?> checked="checked"<?php } ?> />
				</p>
				<p>
					<label for="updates">Check for updates?</label>
					<input id="updates" name="updates" type="checkbox" <?php if(is_checked('updates', $callhome)) { ?> checked="checked"<?php } ?> />
				</p>
				<p>
					<input name="submit" type="submit" value="Save changes" />
				</p>
			</form>
		</div>
<?php include('inc/footer.php'); ?>