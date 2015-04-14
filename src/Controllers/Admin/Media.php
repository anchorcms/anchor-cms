<?php

namespace Controllers\Admin;

use RuntimeException;

class Media extends Backend {

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

	public function upload() {
		$errors = [];
		$uploaded = [];
		$paths = $this->config->get('paths');

		$accepted = [
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		];

		foreach($_FILES as $file) {
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

			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$mime = $finfo->file($file['tmp_name']);

			$ext = array_search($mime, $accepted);

			if(false === $ext) {
				throw new RuntimeException('Invalid file format.');
			}

			$name = $this->formatFileName($file['name'], $ext);
			$dest = $paths['content'] . '/' . $name;

			if(false === move_uploaded_file($file['tmp_name'], $dest)) {
				throw new RuntimeException('Failed to move uploaded file.');
			}

			$uploaded[] = $name;
		}

		header('Content-Type: application/json');
		return json_encode($uploaded);
	}

}
