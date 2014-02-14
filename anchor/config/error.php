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
		/*



		$file = sprintf('%s-%s.log', php_sapi_name(), date('Y-m-d'));


		$line = sprintf('[%s] --> %s'."\n", date('H:i:s'), $e);

		file_put_contents($path.$file, $line, FILE_APPEND);

		$session = curl_init();

		$post = array(
			'message' => $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine(),
			'trace' => json_encode($e->getTrace())
		);

		curl_setopt_array($session, array(
			CURLOPT_URL => 'http://logger.dev.dnsme.co.uk/push',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($post)
		));

		$response = curl_exec($session);

		$line = sprintf('[%s] --> %s'."\n", date('H:i:s'), $response);

		file_put_contents($path.$file, $line, FILE_APPEND);

		curl_close($session);
		*/
	}
);