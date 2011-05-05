<?php
	
//	Return the path of the main directory
	$path = substr(dirname(__FILE__), 0, -5);
	
//	Get the URL path (from http://site.com/ onwards)
//	__DIR__ - $_SERVER['DOCUMENT_ROOT']
	$urlpath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

	include($path . '/core/connect.php');
	include($path . '/core/themes.php');
	include($path . '/core/stats.php');

//	Stops the fatal "reinclusion" error.
//	include($path . '/core/users.php');

	include($path . '/config/settings.php');
	
	$title = 'Add a User';

	include('inc/header.php');

?>

	<ul id="nav">
		<li><a href="<?php echo $urlpath; ?>admin/add">Add A Post</a></li>
		<li><a href="<?php echo $urlpath; ?>admin">Edit Posts</a></li>
		<li><a href="<?php echo $urlpath; ?>admin/metadata">Site Information</a></li>
		<li class="active"><a href="<?php echo $urlpath; ?>admin/users">Users</a></li>
		
		<a class="visit" href="<?php echo $urlpath; ?>">Visit Site <span style="padding-left: 12px; font-size: 10px; -webkit-font-smoothing: none;">&rarr;</span></a>
	</ul>

	<div id="content">
		<div id="left">
		<?php
			//	Do we have all the fields?
			if($_POST['username'] && $_POST['password'] && $_POST['display']) {
			
				if(!user_exists($_POST['username'])) {
					//	Prepare the query
					$query = $db->prepare('insert into users (id, username, password, display, date) values (:null, :user, :pass, :display, :date)');
					
					$null = NULL;
					$insert = $query->execute(array(
						':null' => $null,
						':user' => $_POST['username'],
						':pass' => md5($_POST['password']),
						':display' => $_POST['display'],
						':date' => date("Y-m-d H:i:s"),
					));
					if(!$insert) {
						$error .= 'Unable to add user.';
					} else {
						$error .= 'User added.';
					}
				} else {
					$error .= 'That user already exists!';
				}
			} else {
				$error .= 'Don\'t leave any fields blank!<br />';
			}
		?>
			<h1>Add A New User <a href="<?php echo $urlpath; ?>admin/users">Cancel</a></h1>
			
			<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
			
			<form action="" method="post">
				<p>
					<label for="username">Username:</label>
					<input id="username" name="username" placeholder="Less than 32 characters" value="<?php echo $_POST['username']; ?>" />
				</p>
				<p>
					<label for="password">Password:</label>
					<input id="password" name="password" type="password" placeholder="Secure, but memorable" value="<?php echo $_POST['password']; ?>" />
				</p>
				<p>
					<label for="display">Display name:</label>
					<input id="display" name="display" placeholder="The public-facing name of this account" value="<?php echo $_POST['display']; ?>" />
				</p>
				<p>
					<input name="submit" type="submit" value="Save changes" />
				</p>
			</form>
		</div>
<?php include('inc/footer.php'); ?>