<?php 
	$vars=$this->vars;
	$vars['uri']= Uri::to('admin/posts/add');
	echo View::create('posts/_form',$vars)->render();
?>