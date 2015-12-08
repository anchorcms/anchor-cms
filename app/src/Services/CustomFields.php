<?php

namespace Services;

class CustomFields {

	protected $extend;

	protected $postmeta;

	protected $pagemeta;

	protected $media;

	protected $map;

	public function __construct($extend, $postmeta, $pagemeta) {
		$this->extend = $extend;
		$this->postmeta = $postmeta;
		$this->pagemeta = $pagemeta;
		$this->map = [];
	}

	protected function getFields($type) {
		if(array_key_exists($type, $this->map)) {
			return $this->map[$type];
		}

		return $this->map[$type] = $this->extend->where('type', '=', $type)->get();
	}

	public function getFieldValues($type, $id) {
		$fields = $this->getFields($type);
		$values = [];
		$table = $type.'meta';

		foreach($fields as $field) {
			$meta = $this->$table->where('extend', '=', $field->id)->where($type, '=', $id)->fetch();

			if($meta) $values[$field->key] = json_decode($meta->data, true);
		}

		return $values;
	}

	public function saveFields($request, array $input, $type, $id) {
		$fields = $this->getFields($type);
		$table = $type.'meta';

		$files = $request->getUploadedFiles();

		foreach($fields as $field) {
			if(false === array_key_exists($field->key, $input)) {
				continue;
			}

			if(null === $input[$field->key] && array_key_exists($field->key, $files)) {
				$result = $this->media->upload($files[$field->key]);

				$value = json_encode($result);
			}
			else {
				$value = json_encode($input[$field->key]);
			}

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

	protected function appendTextField($form, $field, $attributes) {
		$input = new \Forms\Elements\Input($field->key, [
			'label' => $field->label,
			'attributes' => $attributes,
		]);

		$form->addElement($input);
	}

	protected function appendHtmlField($form, $field, $attributes) {
		$input = new \Forms\Elements\Textarea($field->key, [
			'label' => $field->label,
			'attributes' => $attributes,
		]);

		$form->addElement($input);
	}

	protected function appendImageField($form, $field, $attributes) {
		$input = new \Forms\Elements\File('_'.$field->key, [
			'label' => $field->label,
			'attributes' => $attributes,
		]);

		$form->addElement($input);

		$input = new \Forms\Elements\Hidden($field->key);

		$form->addElement($input);
	}

	protected function appendFileField($form, $field, $attributes) {
		$input = new \Forms\Elements\File('_'.$field->key, [
			'label' => $field->label,
			'attributes' => $attributes,
		]);

		$form->addElement($input);

		$input = new \Forms\Elements\Hidden($field->key);

		$form->addElement($input);
	}

	public function appendFields($form, $type) {
		$fields = $this->getFields($type);

		foreach($fields as $field) {
			$attributes = json_decode($field->attributes, true) ?: [];
			$method = sprintf('append%sField', ucfirst($field->field));
			$this->{$method}($form, $field, $attributes);
			$form->pushFilter($field->key, FILTER_UNSAFE_RAW);
		}
	}

}
