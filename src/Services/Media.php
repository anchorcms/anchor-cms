<?php

namespace Services;

use FilesystemIterator;
use RuntimeException;

class Media {

	protected $path;

	public function __construct($path) {
		$this->path = realpath($path);
	}

	protected function formatFileName($str, $ext) {
		// convert to ascii
		$str = preg_replace('/[[:^print:]]/', '', $str);

		// lower case
		$str = strtolower($str);

		// remove file extension
		$str = rtrim($str, '.'.$ext);

		// remove white space
		$str = preg_replace('#\s+#', '-', $str);

		return $str . '.' . $ext;
	}

	public function upload($file) {
		switch($file['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException('No file sent.');
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException('Exceeded filesize limit.');
			default:
				throw new RuntimeException('Unknown errors.');
		}

		$accepted = [
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		];

		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($file['tmp_name']);

		$ext = array_search($mime, $accepted);

		if(false === $ext) {
			throw new RuntimeException('Unaccepted file format.');
		}

		$name = $this->formatFileName($file['name'], $ext);
		$dest = $this->path . '/' . $name;

		if(false === move_uploaded_file($file['tmp_name'], $dest)) {
			throw new RuntimeException('Failed to move uploaded file.');
		}

		return $name;
	}

	public function get($filter = null) {
		$fi = new FilesystemIterator($this->path, FilesystemIterator::SKIP_DOTS);
		$files = [];

		foreach($fi as $file) {
			if(false === $file->isFile()) {
				continue;
			}

			if(false === in_array($file->getExtension(), ['jpg', 'png', 'gif'])) {
				continue;
			}

			if(null !== $filter && false === $filter($file)) {
				continue;
			}

			$files[] = [
				'name' => $file->getFilename(),
				'modified' => $file->getMTime(),
			];
		}

		// newest files first
		usort($files, function($a, $b) {
			if($a['modified'] == $b['modified']) {
				return 0;
			}
			return $a['modified'] > $b['modified'] ? -1 : 1 ;
		});

		return $files;
	}

}
