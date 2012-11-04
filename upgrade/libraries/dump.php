<?php

class Dump {

	public static function create($file) {
		$config = require PATH . 'anchor/config/dev/database.php';

		extract($config['connections'][$config['default']]);

		$command = '/usr/bin/mysqldump --opt' .
			' --no-data' .
			' --user ' . $username .
			' --password=' . $password .
			' --host=' . $hostname .
			' --port=' . $port .
			' ' . $database . ' > ' . $file;

		Os::exec($command);

		$command = '/usr/bin/mysqldump' .
			' --skip-triggers --compact --no-create-info' .
			' --user ' . $username .
			' --password=' . $password .
			' --host=' . $hostname .
			' --port=' . $port .
			' --ignore-table=' . $database . '.session' .
			' ' . $database . ' >> ' . $file;

		Os::exec($command);
	}

}