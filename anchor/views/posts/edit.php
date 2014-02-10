<?php 
	$vars=$this->vars;
	$vars['uri']= Uri::to('admin/posts/edit/' . $article->id);
	$vars['isEdit']=true;
	echo View::create('posts/_form',$vars)->render();
?>