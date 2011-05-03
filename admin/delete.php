<?php
	
//	Return the path of the main directory
	$path = substr(dirname(__FILE__), 0, -5);
	
//	Get the URL path (from http://site.com/ onwards)
//	__DIR__ - $_SERVER['DOCUMENT_ROOT']
	$urlpath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

	include($path . '/core/connect.php');
	include($path . '/core/themes.php');
	
	$title = 'Delete A Post';

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
			<div style="padding: 25px;">
			<?php
				$query = $db->prepare('delete from posts where id = :id');
	
				$insert = $query->execute(array(
					':id' => $_GET['page'],
				));
				
				if(!$insert) {
					echo 'Unable to delete post. Try flailing limbs wildly and trying again.';
				} else {
					echo 'Post deleted. <a href="' . $urlpath . 'admin">Go back?</a>';
				}
			?>
					
			</div>
		</div>
<?php include('inc/footer.php'); ?>