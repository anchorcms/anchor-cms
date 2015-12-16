<?php

namespace Models;

class Category extends Model {

	public function postCount() {
		return $this->post_count ?: 0;
	}

}
