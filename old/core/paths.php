<?php

/******************************************************
 *
 *		paths.php
 *
 ******************************************************
 *
 *		Return the correct filepaths: PHP sucks at it.
 *		So do I, however. This should be improved.
 */

//	NOTE: The -5 is to get rid of "core/".

//	Return the path of the main directory
	$path = str_replace('\\', '/', substr(dirname(__FILE__), 0, -4));
	
//	Get the URL path (from http://site.com/ onwards)
//	__DIR__ - $_SERVER['DOCUMENT_ROOT']
	$urlpath = str_ireplace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', $path);
	if (substr($urlpath, 0, 1) != '/') { $urlpath = '/' . $urlpath; }

//	Theme path
	$themepath = $urlpath . 'themes/' . (isset($theme) ? $theme : 'default') . '/';
?>