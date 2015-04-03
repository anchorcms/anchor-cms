<?php

class Migrations {

	private $current;

	public function __construct($current) {
		$this->current = intval($current);
	}

	public function files($reverse = false) {
		$iterator = new FilesystemIterator(APP . 'migrations', FilesystemIterator::SKIP_DOTS);
		$files = array();

		foreach($iterator as $file) {
			$parts = explode('_', $file->getBasename(EXT));
			$num = array_shift($parts);

			$files[$num] = array(
				'path' => $file->getPathname(),
				'class' => 'Migration_' . implode('_', $parts)
			);
		}

		if($reverse) {
			krsort($files, SORT_NUMERIC);
		}
		else {
			ksort($files, SORT_NUMERIC);
		}

		return $files;
	}

	public function up($to = null) {
		// sorted migration files
		$files = $this->files();

		if(is_null($to)) $to = end(array_keys($files));

		// run migrations
		foreach($files as $num => $item) {
			// upto
			if($num > $to) break;

			// starting from
			if($num < $this->current) continue;

			// run
			require $item['path'];

			$m = new $item['class'];

			$m->up();
		}

		return $num;
	}

	public function down($to) {
		// reverse sorted migration files
		$files = $this->files(true);

		if(is_null($to)) $to = current(array_keys($files));

		// run migrations
		foreach($files as $num => $item) {
			// upto
			if($num < $to) break;

			// starting from
			if($num > $this->current) continue;

			// run
			require $item['path'];

			$m = new $item['class'];

			$m->down();
		}

		return $num;
	}

}