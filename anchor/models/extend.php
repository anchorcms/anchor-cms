<?php

class Extend extends Model {

	public static $table = 'extend';

	public static function field($type, $key, $id = -1) {
		$field = Query::table(static::$table)
			->where('type', '=', $type)
			->where('key', '=', $key)
			->fetch();

		if($field) {
			$meta = Query::table($type . '_meta')
				->where($type, '=', $id)
				->where('extend', '=', $field->id)
				->fetch();

			$field->value = Json::decode($meta ? $meta->data : '{}');
		}

		return $field;
	}

	public static function fields($type, $id = -1) {
		$fields = Query::table(static::$table)->where('type', '=', $type)->get();

		foreach(array_keys($fields) as $index) {
			$meta = Query::table($type . '_meta')
				->where($type, '=', $id)
				->where('extend', '=', $fields[$index]->id)
				->fetch();

			$fields[$index]->value = Json::decode($meta ? $meta->data : '{}');
		}

		return $fields;
	}

	public static function html($item) {
		switch($item->field) {
			case 'text':
				$value = isset($item->value->text) ? $item->value->text : '';
				$html = '<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="text" value="' . $value . '">';
				break;

			case 'html':
				$value = isset($item->value->html) ? $item->value->html : '';
				$html = '<textarea id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="text">' . $value . '</textarea>';
				break;

			case 'image':
				$value = isset($item->value->filename) ? $item->value->filename : '';
				$html = '
					<span class="current-file">
						<a href="' . asset('content/' . $value) . '" target="_blank">' . $value . '</a>
					</span>
					<span class="file">
						<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="file" accept="image/*">
					</span>
				';
				break;

			case 'file':
				$value = isset($item->value->filename) ? $item->value->filename : '';
				$html = '
					<span class="current-file">
						<a href="' . asset('content/' . $value) . '" target="_blank">' . $value . '</a>
					</span>
					<span class="file">
						<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="file">
					</span>
				';
				break;

			default:
				$html = '';
		}

		return $html;
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

		return new Paginator($results, $count, $page, $perpage, url('extend'));
	}

	/*
		Process field types
	*/

	public static function files() {
		// format file array
		$files = array();

		foreach($_FILES['extend'] as $label => $items) {
			foreach($items as $key => $value) {
				$files[$key][$label] = $value;
			}
		}

		return $files;
	}

	public static function upload($file) {
		$storage = PATH . 'content' . DS;
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		$filename = hash('crc32', file_get_contents($file['tmp_name'])) . '.' . $ext;
		$filepath = $storage . $filename;

		if(move_uploaded_file($file['tmp_name'], $filepath)) {
			return $filepath;
		}

		return false;
	}

	public static function process_image($extend) {
		$file = array_get(static::files(), $extend->key);

		if($file and $file['error'] === UPLOAD_ERR_OK) {
			$name = basename($file['name']);
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

			if($filepath = static::upload($file)) {
				$filename = basename($filepath);

				// resize image
				if(isset($extend->attributes->size)) {
					$image = Image::open($filepath);

					$image->resize($extend->attributes->size->width, $extend->attributes->size->height);

					$image->output($ext, $filepath);
				}

				return Json::encode(compact('name', 'filename'));
			}
		}
	}

	public static function process_file($extend) {
		$file = array_get(static::files(), $extend->key);

		if($file and $file['error'] === UPLOAD_ERR_OK) {
			$name = basename($file['name']);

			if($filepath = static::upload($file)) {
				$filename = basename($filepath);

				return Json::encode(compact('name', 'filename'));
			}
		}
	}

	public static function process_text($extend) {
		$text = Input::get('extend.' . $extend->key);

		return Json::encode(compact('text'));
	}

	public static function process_html($extend) {
		$html = Input::get('extend.' . $extend->key);

		return Json::encode(compact('html'));
	}

	/*
		Save
	*/

	public static function process($type, $item) {
		foreach(static::fields($type) as $extend) {
			if($extend->attributes) {
				$extend->attributes = Json::decode($extend->attributes);
			}

			$data = call_user_func_array(array('Extend', 'process_' . $extend->field), array($extend, $item));

			// save data
			if( ! is_null($data)) {
				$table = $extend->type . '_meta';
				$query = Query::table($table)
					->where('extend', '=', $extend->id)
					->where($extend->type, '=', $item);

				if($query->count()) {
					$query->update(array('data' => $data));
				}
				else {
					$query->insert(array(
						'extend' => $extend->id,
						$extend->type => $item,
						'data' => $data
					));
				}
			}
		}
	}

}