<?php

class Os {
	public static function exec($command) {
		file_put_contents(APP . 'storage/upgrade.log', date('c') . ' ---> ' . $command . PHP_EOL, FILE_APPEND);

		exec($command);
	}
}