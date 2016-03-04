<?php

namespace Controllers\Admin;

class Themes extends Backend {

	public function getIndex() {
		$vars['title'] = 'Themes';

		return $this->renderTemplate('layouts/default', 'themes/index', $vars);
	}
}
