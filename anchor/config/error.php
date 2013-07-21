<?php

return array(
	/*
	 * Error detail report
	 *
	 * When set to True errors will be show with full details and stack trace
	 * Set to False in production
	 */
	'report' => true,

	/*
	 * Error logging
	 *
	 * The log function is always called regardless of the error detail report.
	 */
	'log' => function($exception) {}
);