<?php

class Extend extends Base {

	public static $table = 'extend';

	public static function validate(&$input, $existing_id = 0) {
		if(empty($input['key'])) {
			$input['key'] = $input['label'];
		}

		$input['key'] = slug($input['key'], '_');

		$validator = new Validator($input);

		$validator->add('valid_key', function($str) use($existing_id) {
			return Extend::where('key', '=', $str)
				->where('id', '<>', $existing_id)->count() == 0;
		});

		$validator->check('key')
			->is_max(1, __('extend.key_missing'))
			->is_valid_key(__('extend.key_exists'));

		$validator->check('label')
			->is_max(1, __('extend.label_missing'));

		return $validator->errors();
	}

	public static function create($input) {
		$class = Type::create($input['field_type'], $input);
		$input['attributes'] = $class::attributes($input);

		return parent::create($input);
	}

	public static function update($id, $input) {
		$class = Type::create($input['field_type'], $input);
		$input['attributes'] = $class::attributes($input);

		return parent::update($id, $input);
	}

	public static function paginate($page = 1, $perpage = 10) {
		$count = static::count();
		$results = static::take($perpage)->skip(($page - 1) * $perpage)->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/extend/fields'));
	}

	/**
	 * Get all custom fields for a data type
	 *
	 * @param string
	 * @param int
	 * @return array
	 */
	public static function fields($type) {
		return static::where('data_type', '=', $type)->get();
	}

	/**
	 * Get field type
	 *
	 * @return object
	 */
	public function type($id = 0) {
		// get custom field data
		$meta = Query::table($this->data_type . '_meta')->where($this->data_type, '=', $id)
			->where('extend', '=', $this->id)->fetch();

		// only set the value if we have data
		if($meta) $this->value = Json::decode($meta->data);

		return Type::create($this->field_type, $this);
	}

	/**
	 * Save posted data in data type meta table
	 *
	 * @param string
	 * @param int
	 */
	public static function save_custom_fields($data_type, $existing_id) {

		// loop custom fields from data type (`post`, `page`)
		foreach(static::fields($data_type) as $custom_field) {
			// build query for data type metadata
			$query = Query::table($custom_field->data_type . '_meta')
				->where('extend', '=', $custom_field->id)
				->where($custom_field->data_type, '=', $existing_id);

			// remove data if requested
			if(Input::get('extend_remove.' . $custom_field->key)) {
				$query->delete();
			}
			else {
				// run custom field save method
				$custom_field_type = Type::create($custom_field->field_type, $custom_field);
				$data = $custom_field_type->save();

				// save output (should be a json string)
				if($query->count()) {
					$query->update(array('data' => $data));
				}
				else {
					$query->insert(array(
						'extend' => $custom_field->id,
						$custom_field->data_type => $existing_id,
						'data' => $data));
				}
			}
		}
	}

}
