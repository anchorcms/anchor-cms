<h1>Site Metadata</h1>

<form action="" method="post" enctype="multipart/form-data">
	<p>
		<label for="sitename">Site name:</label>
		<input id="sitename" name="sitename" value="<?php echo $sitename; ?>" />
	</p>
	<p>
		<label for="theme">Current theme:</label>
			<select name="theme">
			<?php
			if($dh = opendir($path . '/themes')) {
        while(($file = readdir($dh)) !== false) {
          if(($file != '.') && ($file != '..')) {
          	$title = ucwords(str_replace('_', ' ', $file));
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
		<input id="clean" name="clean_urls" type="checkbox"<?php if($clean_urls === true) { echo 'checked="checked"'; } ?> />
	</p>
	<p>
		<label for="updates">Check for updates?</label>
		<input id="updates" name="callhome" type="checkbox"<?php if($callhome === true) { echo 'checked="checked"'; } ?> />
	</p>
	<p>
		<input name="submit" type="submit" value="Save changes" />
	</p>
</form>