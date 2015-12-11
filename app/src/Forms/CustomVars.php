<?php

namespace Forms;

class CustomVars extends Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('key', [
			'label' => 'Key',
		]));

		$this->addElement(new \Forms\Elements\Textarea('value', [
			'label' => 'Value',
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save changes',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function getFilters() {
		return [
			'token' => FILTER_SANITIZE_STRING,
			'key' => FILTER_SANITIZE_STRING,
			'value' => FILTER_UNSAFE_RAW,
		];
	}

	public function getRules() {
		return [
			'key' => ['label' => 'Key', 'rules' => ['required']],
		];
	}

}
