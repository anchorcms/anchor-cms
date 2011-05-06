<?php
	include('loader.php');
	
	$title = 'Add a Post';

	include('inc/header.php');

?>
	<ul id="nav">
		<li class="active"><a href="<?php echo $urlpath; ?>admin/add">Add A Post</a></li>
		<li><a href="<?php echo $urlpath; ?>admin">Edit Posts</a></li>
		<li><a href="<?php echo $urlpath; ?>admin/metadata">Site Information</a></li>
		<li><a href="<?php echo $urlpath; ?>admin/users">Users</a></li>
		
		<a class="visit" href="<?php echo $urlpath; ?>">Visit Site <span style="padding-left: 12px; font-size: 10px; -webkit-font-smoothing: none;">&rarr;</span></a>
	</ul>

	<div id="content">
		<div id="left">
		<?php
			$error = '';
			
		//	These variables have caused me so much pain.
		//	Please send them hate mail.
			$jsfile = '';
			$cssfile = '';
			
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
				
				//	Check variables
				if($_POST['posttitle'] && ctype_alnum($_POST['postslug']) && $_POST['postexcerpt'] && $_POST['posthtml']) {
				
					$query = $db->prepare('insert into posts (id, slug, title, excerpt, content, css, javascript, date) values (:null, :slug, :title, :excerpt, :content, :css, :js, :date)');
					
					$null = NULL;
					$insert = $query->execute(array(
						':null' => $null,
						':title' => $_POST['posttitle'],
						':slug' => $_POST['postslug'],
						':excerpt' => $_POST['postexcerpt'],
						':content' => $_POST['posthtml'],
						':css' => $cssfile,
						':js' => $jsfile,
						':date' => date("Y-m-d H:i:s"),
					));
					if(!$insert) {
						$error .= 'Unable to add post.';
					} else {
						$error .= 'Post added.';
					}
				} elseif(!ctype_alnum($_POST['postslug'])) {
					$error .= 'Post slug must be alphanumeric.';
				}  else {
					$error .= 'Don\'t leave any fields blank!<br />';
				}
			}
		?>
			<h1>Add A New Post <a href="<?php echo $urlpath; ?>admin">Cancel</a></h1>
			
			<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
			
			<form action="" method="post" enctype="multipart/form-data">
				<p>
					<label for="posttitle">Post title:</label>
					<input id="posttitle" name="posttitle" value="<?php echo isset($obj->title) ? $obj->title : ''; ?>" />
				</p>
				<p>
					<label for="postslug">Post slug:</label>
					<input id="postslug" class="monospace" name="postslug" value="<?php echo isset($obj->slug) ? $obj->slug : ''; ?>" />
				</p>
				<p>
					<label for="postexcerpt">Post excerpt:</label>
					<textarea id="postexcerpt" name="postexcerpt"><?php echo isset($obj->excerpt) ? $obj->excerpt : ''; ?></textarea>
				</p>
				<p>
					<label for="posthtml">Post HTML:</label>
					<textarea id="posthtml" class="monospace" name="posthtml"><?php echo isset($obj->content) ? $obj->content : ''; ?></textarea>
				</p>
				<p>
					<label for="postcss">Post CSS:</label>
					<input type="file" id="postcss" name="postcss" />
				</p>
				<p>
					<label for="postjs">Post Javascript:</label>
					<input type="file" id="postjs" name="postjs" />
				</p>
				<p>
					<input name="submit" type="submit" value="Save changes" />
				</p>
			</form>
		</div>
<?php include('inc/footer.php'); ?>