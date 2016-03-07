<?php

class Slugify {

	public function parse($str) {
		$str = preg_replace('#[^A-Za-z0-9\s]+#', '', html_entity_decode($str, ENT_QUOTES, 'UTF-8'));

		return preg_replace('#\s+#', '-', strtolower($str));
	}

}
