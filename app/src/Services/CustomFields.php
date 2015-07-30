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
		$table = $type.'meta';

		foreach($fields as $field) {
			$meta = $this->$table->where('extend', '=', $field->id)->where($type, '=', $id)->fetch();

			if($meta) $values[$field->key] = json_decode($meta->data, true);
		}

		return $values;
	}

	public function saveFields(array $input, $type, $id) {
		$fields = $this->extend->where('type', '=', $type)->get();
		$table = $type.'meta';

		foreach($fields as $field) {
			if(false === array_key_exists($field->key, $input)) {
				continue;
			}

			$this->$table->insert([
				$type => $id,
				'extend' => $field->id,
				'data' => json_encode($input[$field->key]),
			]);
		}
	}

	public function updateFields(array $input, $type, $id) {
		$fields = $this->extend->where('type', '=', $type)->get();
		$table = $type.'meta';

		foreach($fields as $field) {
			if(false === array_key_exists($field->key, $input)) {
				continue;
			}

			$value = json_encode($input[$field->key]);

			$query = $this->$table->where($type, '=', $id)->where('extend', '=', $field->id);
			$count = clone $query;

			if($count->count()) {
				$query->update(['data' => $value]);
			}
			else {
				$this->$table->insert([
					$type => $id,
					'extend' => $field->id,
					'data' => $value,
				]);
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
