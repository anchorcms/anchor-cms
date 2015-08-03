<?php

namespace Forms\Traits;

trait FilterRules {

	protected $rules = [];

	public function getRules() {
		return $this->rules;
	}

	public function setRules(array $rules) {
		$this->rules = $rules;
	}

	public function withRules(array $rules) {
		$this->setRules($rules);

		return $this;
	}

	public function pushRule($key, $value) {
		$this->filters[$key] = $value;
	}

}
