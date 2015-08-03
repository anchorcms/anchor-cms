<?php

namespace Controllers\Admin;

class Extend extends Backend {

	public function getIndex() {
		$vars['title'] = 'Extend';

		return $this->renderTemplate('main', ['extend/index'], $vars);
	}

}
