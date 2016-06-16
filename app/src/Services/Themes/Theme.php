<?php

namespace Anchorcms\Services\Themes;

class Theme extends \View {

	protected $path;

	protected $name;

	protected $active = false;

	protected $manifest;

	protected $layout;

	public function __construct($path) {
		$this->ext = '.php';
		$this->path = $path;
		$this->name = basename($path);

		if(is_file($this->path . '/manifest.json')) {
			$this->manifest = json_decode(file_get_contents($this->path . '/manifest.json'));
			$this->ext = $this->manifest->extension;

			if(is_file($this->path . '/layout.' . $this->ext)) {
				$this->layout = $this->path . '/layout.' . $this->ext;
			}
		}
	}

	/*
	 *
	 */
	public function hasManifest() {
		return null !== $this->manifest;
	}

	/*
	 *
	 */
	public function getManifest() {
		return $this->manifest;
	}

	/*
	 *
	 */
	public function setActive() {
		$this->active = true;
	}

	/*
	 *
	 */
	public function isActive() {
		return $this->active;
	}

	/*
	 *
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the first template that exists
	 *
	 * @param array
	 * @return string
	 */
	protected function getTemplate(array $names) {
		foreach($names as $name) {
			if($this->templateExists($name)) {
				return $name;
			}
		}

		throw new \ErrorException(sprintf('Template not found: %s', $name));
	}

	/*
	 *
	 */
	public function renderTemplate(array $templates, array $vars = []) {
		$files = [
			$this->paths['app'] . '/functions.php',
		];

		if(is_file($this->path . '/functions.php')) {
			$files[] = $this->path . '/functions.php';
		}

		if($this->layout) {
			$template = $this->getTemplate($templates);
			$vars['body'] = $this->render($template, $vars, $files);

			$template = $this->getTemplate([$this->layout]);
			return $this->render($template, $vars, $files);
		}

		$template = $this->getTemplate($templates);
		return $this->render($template, $vars, $files);
	}

}
