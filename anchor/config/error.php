<?php

return array(

	// Here you simply specify the error levels that should be ignored by the
	// Laravel error handler. These levels will still be logged; however, no
	// information about about them will be displayed.
	'ignore' => array(E_DEPRECATED, E_STRICT),

	// Detailed error messages contain information about the file in which an
	// error occurs, as well as a PHP stack trace containing the call stack.
	'detail' => true,

	// When error logging is enabled, the "logger" Closure defined below will
	// be called for every error in your application.
	'log' => false,

	// Because of the various ways of managing error logging, you get complete
	// flexibility to manage error logging as you see fit. This function will
	// be called anytime an error occurs within your application and error
	// logging is enabled.
	'logger' => function($exception) {}

);