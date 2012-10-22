<?php

class Extend extends Model {

	public static $table = 'extend';

	public static function fields($type, $id = -1) {
		$fields = Query::table(static::$table)->where('type', '=', $type)->get();

		foreach(array_keys($fields) as $index) {
			$meta = Query::table($type . '_meta')
				->where($type, '=', $id)
				->where('extend', '=', $fields[$index]->id)
				->fetch();

			$fields[$index]->value = $meta ? $meta->data : '';
		}

		return $fields;
	}

	public static function html($item) {
		switch($item->field) {
			case 'text':
				return '<input id="' . $item->key . '" name="' . $item->key . '" type="text" value="' . $item->value . '">';
			case 'html':
				return '<textarea id="' . $item->key . '" name="' . $item->key . '" type="text">' . $item->value . '</textarea>';
			case 'image':
				return '<input id="' . $item->key . '" name="' . $item->key . '" type="file" accept="image/*">';
			case 'file':
				return '<input id="' . $item->key . '" name="' . $item->key . '" type="file">';
		}
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

		return new Paginator($results, $count, $page, $perpage, url('extend'));
	}

	public static function process_image($extend, &$meta) {

		if(isset($_FILES['extend_' . $extend->key])) {

			$file = $_FILES['extend_' . $extend->key];

			if($file['error'] === UPLOAD_ERR_OK) {

				$name = basename($file['name']);
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$storage = PATH . 'content' . DS;
				$filename = hash('crc32', $name);
				$filepath = $storage . $filename . '.' . $ext;

				if(move_uploaded_file($file['tmp_name'], $filepath)) {

					// resize image
					if(isset($extend->attributes['size'])) {
						$image = Image::open($filepath);

						$image->resize($extend->attributes['size']['width'], $extend->attributes['size']['height']);

						$image->output($ext, $filepath);
					}

					// save data
					$meta[] = array(
						'extend' => $extend->id,
						'data' => Json::encode(compact($name, $filepath))
					);

				}

			}

		}

	}

	public static function process_file($extend, &$meta) {

		if(isset($_FILES['extend_' . $extend->key])) {

			$file = $_FILES['extend_' . $extend->key];

			if($file['error'] === UPLOAD_ERR_OK) {

				$name = basename($file['name']);
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$storage = PATH . 'content' . DS;
				$filename = hash('crc32', $name);
				$filepath = $storage . $filename . '.' . $ext;

				if(move_uploaded_file($file['tmp_name'], $filepath)) {

					// save data
					$meta[] = array(
						'extend' => $extend->id,
						'data' => Json::encode(compact($name, $filepath))
					);

				}

			}

		}

	}

	public static function process_text($extend, &$meta) {
		$text = Input::get('extend_' . $extend->key);

		// save data
		$meta[] = array(
			'extend' => $extend->id,
			'data' => Json::encode(compact($text))
		);
	}

	public static function process_html($extend, &$meta) {
		$html = Input::get('extend_' . $extend->key);

		// save data
		$meta[] = array(
			'extend' => $extend->id,
			'data' => Json::encode(compact($html))
		);
	}

	public static function process() {
		$meta = array();

		foreach(static::fields('post') as $extend) {
			if($extend->attributes) {
				$extend->attributes = Json::decode($extend->attributes);
			}

			call_user_func_array(array('Extend', 'process_' . $extend->field), array($extend, $meta));
		}

		return $meta;
	}

}