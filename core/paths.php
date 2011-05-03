<?php

/******************************************************
 *
 *		paths.php						by @visualidiot
 *
 ******************************************************
 *
 *		Return the correct filepaths: PHP sucks at it.
 *		So do I, however. This should be improved.
 */

//	NOTE: The -5 is to get rid of "core/".


//	Return the path of the main directory
	$path = substr(dirname(__FILE__), 0, -5);
	
//	Get the URL path (from http://site.com/ onwards)
//	__DIR__ - $_SERVER['DOCUMENT_ROOT']
	$urlpath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
?>