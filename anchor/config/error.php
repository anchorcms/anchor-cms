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
	'log' => function($e) {
		$prefix = date('H:i:s').PHP_EOL.str_repeat('-', 80).PHP_EOL;
		$filename = php_sapi_name().'-'.date('Y-m-d').'.log';
		file_put_contents(__DIR__.'/../logs/'.$filename, $prefix.$e.PHP_EOL.PHP_EOL, FILE_APPEND);
	}
);