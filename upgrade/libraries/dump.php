<?php

class Dump {

	public static function create($file) {
		$config = Session::get('config');

		extract($config['database']);

		$command = '/usr/bin/mysqldump --opt' .
			' --no-data' .
			' --user ' . $user .
			' --password=' . $pass .
			' --host=' . $host .
			' --port=' . $port .
			' ' . $name . ' > ' . $file;

		Os::exec($command);

		$command = '/usr/bin/mysqldump' .
			' --skip-triggers --compact --no-create-info' .
			' --user ' . $user .
			' --password=' . $pass .
			' --host=' . $host .
			' --port=' . $port .
			' --ignore-table=' . $name . '.session' .
			' ' . $name . ' >> ' . $file;

		Os::exec($command);
	}

}