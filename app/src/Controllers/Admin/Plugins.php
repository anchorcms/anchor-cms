<?php

namespace Controllers\Admin;

class Plugins extends Backend {

	public function getIndex() {
		$plugins = $this->plugins->getPlugins();

		$vars['title'] = 'Plugins';
		$vars['plugins'] = $plugins;
		$vars['messages'] = $this->messages->render();

		return $this->renderTemplate('main', ['plugins/index'], $vars);
	}

}
