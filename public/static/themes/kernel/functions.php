<?php 

/**
 *   Theme option
 *   Usage idea
 */

//  Default usage.
//  Assumes `type` is "text" and gives a standard textbox.
theme_option('site_intro', 'Welcome to my site!');

//  Full usage
theme_option('option_name', array(
	//  Set a option type.
	//  text, image, file, textarea, or colour, or select.
	'type' => 'textarea',
	
	//  Set the fallback value if not set in theme options.
	'default' => 'This is my default value.',
	
	//  If you checked "select" as type, you'll need to
	//  provide an array with the values to pick.
	//
	//  If the array is one-dimensional, the key and
	//  value will be the same. If not, the key will
	//  be returned in theme_option() and the value will
	//  be displayed in the theme options screen.
	'options' => array(
		'default' => 'Default colour scheme',
		'shiny' => 'My shiny colour scheme'
	)
));

//  To display, just echo the function, only providing the
//  option name as a parameter.
//
//  Make sure to only call the one parameter.
echo theme_option('site_intro');