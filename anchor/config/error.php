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
		$file = sprintf('%s.log', php_sapi_name());
		$path = __DIR__ . '/../logs/';

		$log = new Monolog\Logger('app');
		$log->pushHandler(new Monolog\Handler\RotatingFileHandler($path.$file));

		$log->addError($e);
	}
);