<?php

namespace Controllers\Admin;

class Media extends Backend {

	public function postUpload($request) {
		try {
			$files = $request->getUploadedFiles();
			$url = '/content' . $this->media->upload($files['file']);
			$response = ['result' => true, 'path' => $url];
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
