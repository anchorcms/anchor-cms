<?php

namespace Anchorcms\Models;

class Category extends AbstractModel {

	public function postCount() {
		return $this->post_count ?: 0;
	}

}
