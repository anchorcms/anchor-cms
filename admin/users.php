<?php
include('loader.php');
	
	$title = 'Manage Users';

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
			<?php if(!isset($_GET['page'])) { ?>
				<h1>Current users <a href="<?php echo $urlpath; ?>admin/users/add">+ Add A User</a></h1>
				<ul id="list">
				<?php
					$query = $db->query('select * from users order by date desc');
					
					while($row = $query->fetch(PDO::FETCH_ASSOC)) {
						echo '<li><a href="' . $urlpath . 'admin/users/edit/' . $row['id'] . '" title="' . $row['username'] . '">' . $row['display'] . ' (' . $row['username'] . ') <span>' . time_ago(strtotime($row['date'])) . '</span><img src="' . $urlpath . '/core/img/edit_link.png" alt="Edit this post" /></a></li>';
					}
			 ?>
			</ul>
			<?php } else {
				$query = $db->prepare('select * from users where id = :id');
				$query->execute(array(':id' => $_GET['page']));

				$obj = $query->fetch(PDO::FETCH_OBJ);
				
				$error = '';
				
				if(isset($_POST['submit'])) {
				
					//	CSS
					
					if(!empty($_FILES['postcss']['name'])) {
						$css = explode('.', $_FILES['postcss']['name']);
						if(end($css) == 'css') {
							$move = $path . '/uploads/' . basename($_FILES['postcss']['name']); 
							
							if(move_uploaded_file($_FILES['postcss']['tmp_name'], $move)) {
								$cssfile = $path . '/uploads/' . basename($_FILES['postcss']['name']);
							} else {
							    $error .= "There was an error uploading the file, please try again!<br />";
							}
						} else {
							$error = 'Please upload a CSS file.<Br />';
						}
					}
	
					//	JS
					
					if(!empty($_FILES['postjs']['name'])) {
						$js = explode('.', $_FILES['postjs']['name']);
						if(end($js) == 'js') {
							$move = $path . '/uploads/' . basename($_FILES['postjs']['name']); 
							
							if(move_uploaded_file($_FILES['postjs']['tmp_name'], $move)) {
								$jsfile = $path . '/uploads/' . basename($_FILES['postcss']['name']);
							} else {
								$error .= "There was an error uploading the file, please try again!<br />";
							}
						} else {
							$error .= 'Please upload a JavaScript file.<br />';
						}
					}
					
					//
					if($_POST['username'] && $_POST['display']) {
						if(!$_POST['password']) {
							$query = $db->prepare('update users set username = :user, display = :display where id = :id');
							$exec = $query->execute(array(':user' => $_POST['username'], ':display' => $_POST['display'], ':id' => $_GET['page']));
						} else {
							$query = $db->prepare('update users set username = :user, password = :pass, display = :display where id = :id');
							$exec = $query->execute(array(':user' => $_POST['username'], ':pass' => md5($_POST['password']), ':display' => $_POST['display'], ':id' => $_GET['page']));
						}
						
						if($exec) {
							$error .= 'User updated.';
						} else {
							$error .= 'Something went wrong when updating the user.';
						}
					} else {
						$error .= 'You\'ve gotta give me a display name and a username!<br />';
					}
				}
			?>
				<h1>Editing <?php echo $obj->username; ?>&rsquo;s profile <a href="<?php echo $urlpath; ?>admin/users">Cancel</a></h1>
				
				<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
				
				<form action="" method="post" enctype="multipart/form-data">
					<p>
						<label for="username">Username:</label>
						<input id="username" name="username" value="<?php echo $obj->username; ?>" />
					</p>
					<p>
						<label for="password">Password:</label>
						<input id="password" name="password" type="password" value="" placeholder="Leave blank for no change." />
					</p>
					<p>
						<label for="display">Display name:</label>
						<input id="display" name="display" value="<?php echo $obj->display; ?>" />
					</p>
					<p>
						<a class="delete" href="<?php echo $urlpath; ?>admin/users/delete/<?php echo $_GET['page']; ?>">Delete this user?</a>
						<input name="submit" type="submit" value="Save changes" />
					</p>
				</form>
			<?php } ?>
		</div>
<?php include('inc/footer.php'); ?>