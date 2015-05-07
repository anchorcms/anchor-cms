<?php

namespace Controllers\Admin;

class Media extends Backend {

	public function upload() {
		try {
			$name = $this->media->upload($_FILES['file']);
			$response = ['result' => true, 'name' => $name];
		} catch(\Exception $e) {
			$response = ['result' => false, 'message' => $e->getMessage()];
		}

		header('Content-Type: application/json');
		return json_encode($response);
	}

	public function fetch() {
		if($since = filter_input(INPUT_GET, 'since', FILTER_SANITIZE_STRING)) {
			$files = $this->media->get(function($file) use($since) {
				return $file->getMTime() > $since;
			});
		}
		else {
			$files = $this->media->get();
		}

		header('content-type: application/json');
		return json_encode(['result' => true, 'files' => $files]);
	}
}
