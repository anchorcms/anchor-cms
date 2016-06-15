<?php

namespace Anchorcms\Controllers\Admin;

class Content extends Backend {

	public function postPreview($request) {
		$makrdown = filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW);
		$html = $this->container['markdown']->convertToHtml($makrdown);
		return $this->jsonResponse(['result' => true, 'html' => $html]);
	}

}
