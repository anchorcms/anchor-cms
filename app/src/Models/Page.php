<?php

namespace Anchorcms\Models;

class Page extends AbstractModel {

	public function url() {
		return sprintf('/%s', $this->slug);
	}

}
