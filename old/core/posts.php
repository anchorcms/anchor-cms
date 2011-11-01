<?php

/******************************************************
 *
 *		posts.php						by @visualidiot
 *
 ******************************************************
 *
 *		Retrieve, add, and edit posts.
 */
 

//	Get all of the most recent posts
	function get_posts($order = 'date', $display = 'list') {
		require('connect.php');
		require($path . '/config/settings.php');
		
		$get = $db->prepare('SELECT id, slug, title FROM posts ORDER BY :order DESC');
		$get->bindParam(':order', $order, PDO::PARAM_STR);
		$get->execute();
		$get->setFetchMode(PDO::FETCH_OBJ);
		
		//	TODO: Other display methods		
		if($display == 'list') {
			echo '<ul id="list">';
			while($post = $get->fetch()) {
				if($_SERVER['HTTP_MOD_REWRITE'] == 'On' && $clean_urls) {
					echo '<li class="post_' . $post->id . '"><a href="' . $urlpath . $post->slug . '" title="' . $post->title . '">' . $post->title . '</a></li>';
				} else {
					echo '<li class="post_' . $post->id . '"><a href="?page=' . $post->slug . '" title="' . $post->title . '">' . $post->title . '</a></li>';

				}
			}	
			echo '</ul>';	
		}
	}

//	Retrieve an excerpt from a slug or ID
	function get_excerpt($what = 'slug', $input = '', $display = true) {
		require('connect.php');
		
		if($input != '') { $get = $input; } else { $get = $_GET['page']; }

		if($what == 'slug') {
			$get = $db->prepare('SELECT excerpt FROM posts WHERE slug = :slug');
			$get->bindParam(':slug', $get, PDO::PARAM_STR);
		} else {
			$get = $db->prepare('SELECT excerpt FROM posts WHERE id = :id');		
			$get->bindParam(':id', $get, PDO::PARAM_STR);
		}
		$get->execute();
		$get->setFetchMode(PDO::FETCH_ASSOC);
		
		$excerpt = $get->fetch();
		
		//	TODO: Other display methods		
		if($display) echo $excerpt['excerpt'];
		return $excerpt['excerpt'];
	}
	
//	Get the post's slug
	function post_slug($display = true) {
		if($display) echo $_GET['page'];
		return $_GET['page'];
	}
	
//	Get the post's content
	function get_post_content($display = true) {
		if(!is_home()) {
			$slug = $_GET['page'];
			
			include('connect.php');		
		
			$get = $db->prepare('SELECT content FROM posts WHERE slug = :slug');
			$get->execute(array(':slug' => $slug));
			
			$fetch = $get->fetch(PDO::FETCH_OBJ);
			
			if($display) echo $fetch->content;
						 return $fetch->content;
			
		} else {
			//	Why are you trying to get content on a homepage?
			return false;
		}
	}
	
//	Get post's ID from a slug
function get_post_id($slug = '', $display = true) {
	if($slug == '') { $slug = $_GET['page']; }
	
	include('connect.php');		
	
	$get = $db->prepare('SELECT id FROM posts WHERE slug = :slug');
	$get->execute(array(':slug' => $slug));
	
	$return = $get->fetch(PDO::FETCH_OBJ);		
	
	if($display) echo $return->id;
	return $return->id;
} 

//	Get post's title from a slug
function get_post_title($slug = '', $display = true) {
	if($slug == '') { $slug = $_GET['page']; }
	
	include('connect.php');		
	
	$get = $db->prepare('SELECT title FROM posts WHERE slug = :slug');
	$get->execute(array(':slug' => $slug));
	
	$return = $get->fetch(PDO::FETCH_OBJ);		
	
	if($display) echo $return->title;
	return $return->title;
} 

		
?>