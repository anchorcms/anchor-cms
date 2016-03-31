<?php

namespace Controllers\Admin;

class Themes extends Backend {

	public function getIndex() {
		$vars['title'] = 'Themes';
		$vars['themes'] = $this->container['services.themes']->getThemes();

		return $this->renderTemplate('layouts/default', 'themes/index', $vars);
	}

	public function postActivate() {
		$theme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING);

		$this->container['mappers.meta']->where('key', '=', 'theme')->update(['value' => $theme]);

		$this->container['messages']->success(['Theme updated']);
		return $this->redirect($this->container['url']->to('/admin/themes'));
	}

}
