<?php

namespace Anchorcms\Forms\Installer;

class Metadata extends \Forms\Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Input('site_name', [
			'label' => 'Site Name',
			'value' => 'My First Anchor Blog',
		]));

		$this->addElement(new \Forms\Elements\Input('site_description', [
			'label' => 'Site Description',
			'value' => 'It&rsquo;s not just any blog. It&rsquo;s an Anchor blog.',
		]));

		$this->addElement(new \Forms\Elements\Input('site_path', [
			'label' => 'Site Path',
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Next',
			'attributes' => ['class' => 'button button--primary float--right']
		]));
	}

	public function getFilters() {
		return [
			'site_name' => FILTER_SANITIZE_STRING,
			'site_description' => FILTER_SANITIZE_STRING,
			'site_path' => FILTER_SANITIZE_STRING,
		];
	}

	public function getRules() {
		return [
			'site_name' => ['required'],
			'site_description' => ['required'],
			'site_path' => ['required'],
		];
	}

}
