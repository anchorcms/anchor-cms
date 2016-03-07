<?php

namespace Controllers\Admin;

class Plugins extends Backend {

	public function getIndex() {
		$plugins = $this->plugins->getPlugins();

		$vars['title'] = 'Plugins';
		$vars['plugins'] = $plugins;

		return $this->renderTemplate('layouts/default', 'plugins/index', $vars);
	}

}
