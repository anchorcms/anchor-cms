<?php

namespace Controllers\Admin;

class Media extends Backend {

	public function getIndex() {
		if($since = filter_input(INPUT_GET, 'since', FILTER_SANITIZE_STRING)) {
			$files = $this->container['services.media']->get(function($file) use($since) {
				return $file->getMTime() > $since;
			});
		}
		else {
			$files = $this->container['services.media']->get();
		}

		return $this->jsonResponse(['result' => true, 'files' => $files]);
	}

	public function postUpload($request) {
		try {
			$files = $request->getUploadedFiles();
			$url = '/content' . $this->container['services.media']->upload($files['file']);
			$response = ['result' => true, 'path' => $url];
		} catch(\Exception $e) {
			$response = ['result' => false, 'message' => $e->getMessage()];
		}

		return $this->jsonResponse($response);
	}

}
