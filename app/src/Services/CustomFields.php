<?php

namespace Services;

class CustomFields {

	protected $extend;

	protected $postmeta;

	protected $pagemeta;

	public function __construct($extend, $postmeta, $pagemeta) {
		$this->extend = $extend;
		$this->postmeta = $postmeta;
		$this->pagemeta = $pagemeta;
	}

	public function getFieldValues($type, $id) {
		$fields = $this->extend->where('type', '=', $type)->get();
		$values = [];

		foreach($fields as $field) {
			if($type == 'post') {
				$meta = $this->postmeta->where('extend', '=', $field->id)->where('post', '=', $id)->fetch();
			}
			else {
				$meta = $this->pagemeta->where('extend', '=', $field->id)->where('page', '=', $id)->fetch();
			}

			if($meta) {
				$values[$field->key] = json_decode($meta->data, true);
			}
		}

		return $values;
	}

	public function saveFields(array $input, $type, $id) {
		$fields = $this->extend->where('type', '=', $type)->get();

		foreach($fields as $field) {

			if(false === array_key_exists($field->key, $input)) {
				continue;
			}

			$value = json_encode($input[$field->key]);

			if($type == 'post') {
				$this->postmeta->insert([
					'post' => $id,
					'extend' => $field->id,
					'data' => $value,
				]);
			}
			else {
				$this->pagemeta->insert([
					'page' => $id,
					'extend' => $field->id,
					'data' => $value,
				]);
			}

		}
	}

	public function updateFields(array $input, $type, $id) {
		$fields = $this->extend->where('type', '=', $type)->get();

		foreach($fields as $field) {

			if(false === array_key_exists($field->key, $input)) {
				continue;
			}

			$value = json_encode($input[$field->key]);

			if($type == 'post') {
				$query = $this->postmeta->where('post', '=', $id)->where('extend', '=', $field->id);
				$count = clone $query;

				if($count->count()) {
					$query->update(['data' => $value]);
				}
				else {
					$this->postmeta->insert([
						'post' => $id,
						'extend' => $field->id,
						'data' => $value,
					]);
				}
			}
			else {
				$query = $this->pagemeta->where('page', '=', $id)->where('extend', '=', $field->id);
				$count = clone $query;

				if($count->count()) {
					$query->update(['data' => $value]);
				}
				else {
					$this->pagemeta->insert([
						'page' => $id,
						'extend' => $field->id,
						'data' => $value,
					]);
				}
			}

		}
	}

	public function appendFields($form, $type) {
		$fields = $this->extend->where('type', '=', $type)->get();

		foreach($fields as $field) {

			$attributes = json_decode($field->attributes, true) ?: [];

			switch($field->field) {
				case 'text':
					$class = '\\Forms\\Elements\\Input';
					break;

				case 'html':
					$class = '\\Forms\\Elements\\Textarea';
					break;

				case 'image':
				case 'file':
					$class = '\\Forms\\Elements\\File';
					break;
			}

			$input = new $class($field->key, [
				'label' => $field->label,
				'attributes' => $attributes,
			]);

			$form->addElement($input);

			$form->pushFilter($field->key, FILTER_UNSAFE_RAW);
		}
	}

}
