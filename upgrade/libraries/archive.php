<?php

class Archive {

	public static function create($path, $archive, $exclude = array()) {
		$directory = dirname($path);
		$file = basename($path);

		if(file_exists($archive)) unlink($archive);

		$command = 'tar --create' .
			' --file=' . $archive .
			' --directory=' . $directory .
			' --add-file=' . $file;

		if($exclude) {
			$command .= ' --exclude=' . implode(',', $exclude);
		}

		Os::exec($command);
	}

	public static function append($path, $archive) {
		$directory = dirname($path);
		$file = basename($path);

		$command = 'tar --append' .
			' --file=' . $archive .
			' --directory=' . $directory .
			' --add-file=' . $file;

		Os::exec($command);
	}

	public static function compress($archive) {
		if(file_exists($archive . '.gz')) unlink($archive . '.gz');

		$command = 'gzip ' . $archive;

		Os::exec($command);
	}

}