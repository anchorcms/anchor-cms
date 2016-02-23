<?php

class Extend extends Base {

	public static $table = 'extend';

	public static $types = array(
		'post' => 'post',
		'page' => 'page',
		'category' => 'category',
		'user' => 'user'
	);

	public static $field_types = array(
		'text' => 'text',
		'html' => 'html',
		'image' => 'image',
		'file' => 'file'
	);

	public static function field($type, $key, $id = -1) {
		$field = Query::table(static::table())
			->where('type', '=', $type)
			->where('key', '=', $key)
			->fetch();

		if($field) {
			$meta = Query::table(static::table($type . '_meta'))
				->where($type, '=', $id)
				->where('extend', '=', $field->id)
				->fetch();

			$field->value = Json::decode($meta ? $meta->data : '{}');
		}

		return $field;
	}

	public static function value($extend, $value = null) {
		switch($extend->field) {
			case 'text':
				if( ! empty($extend->value->text)) {
					$value = $extend->value->text;
				}
				break;

			case 'html':
				if( ! empty($extend->value->html)) {
					$md = new Markdown;

					$value = $md->transform($extend->value->html);
				}
				break;

			case 'image':
			case 'file':
				if( ! empty($extend->value->filename)) {
					$value = asset('content/' . $extend->value->filename);
				}
				break;
		}

		return $value;
	}

	public static function fields($type, $id = -1, $pagetype = null) {
		if (is_null($pagetype)) {
			$fields = Query::table(static::table())->where('type', '=', $type)->get();
		} else {
			$fields = Query::table(static::table())->where_in('pagetype', array($pagetype, 'all'))->where('type', '=', $type)->get();
		}

		foreach(array_keys($fields) as $index) {
			$meta = Query::table(static::table($type . '_meta'))
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
				$html = '<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="text" value="' . htmlentities($value) . '">';
				break;

			case 'html':
				$value = isset($item->value->html) ? $item->value->html : '';
				$html = '<textarea id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="text">' . $value . '</textarea>';
				break;

			case 'image':
			case 'file':
				$value = isset($item->value->filename) ? $item->value->filename : '';

				$html = '<span class="current-file">';

				if($value) {
					$html .= '<a href="' . asset('content/' . $value) . '" target="_blank">' . $value . '</a>';
				}

				$html .= '</span>
					<span class="file">
					<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="file">
					</span>';

				if($value) {
					$html .= '</p><p>
					<label>' . __('global.delete') . ' ' . $item->label . ':</label>
					<input type="checkbox" name="extend_remove[' . $item->key . ']" value="1">';
				}

				break;

			default:
				$html = '';
		}

		return $html;
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/extend/fields'));
	}

	/*
		Process field types
	*/

	public static function files() {
		// format file array
		$files = array();

		if(isset($_FILES['extend'])) {
			foreach($_FILES['extend'] as $label => $items) {
				foreach($items as $key => $value) {
					$files[$key][$label] = $value;
				}
			}
		}

		return $files;
	}

	public static function upload($file) {
		$uploader = new Uploader(PATH . 'content', array('png', 'jpg', 'bmp', 'gif', 'pdf'));
		$filepath = $uploader->upload($file);

		return $filepath;
	}

	public static function process_image($extend) {
		$file = Arr::get(static::files(), $extend->key);

		if($file and $file['error'] === UPLOAD_ERR_OK) {
			$name = basename($file['name']);
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

			if($filepath = static::upload($file)) {
				$filename = basename($filepath);
				self::resizeImage($filepath);
			}
		}

		// Handle images which have been uploaded indirectly
		// not as files.
		$image_upload = Input::get('extend.' . $extend->key);
		if ($image_upload) {
			$filename = basename($image_upload);
			$name = $filename;
		}

		$data = compact('name', 'filename');
		return Json::encode($data);
	}

	private static function resizeImage($extend, $filepath) {
		// resize image
		if(isset($extend->attributes->size->width) and isset($extend->attributes->size->height)) {
			$image = Image::open($filepath);

			$width = intval($extend->attributes->size->width);
			$height = intval($extend->attributes->size->height);

			// resize larger images
			if(
				($width and $height) and
				($image->width() > $width or $image->height() > $height)
			) {
				$image->resize($width, $height);

				$image->output($ext, $filepath);
			}
		}
	}

	public static function process_file($extend) {
		$file = Arr::get(static::files(), $extend->key);

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
		foreach(static::fields($type, $item) as $extend) {
			if($extend->attributes) {
				$extend->attributes = Json::decode($extend->attributes);
			}

			$data = call_user_func_array(array('Extend', 'process_' . $extend->field), array($extend, $item));

			// save data
			if( ! is_null($data)) {
				$table = static::table($extend->type . '_meta');
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

			// remove data
			if(Input::get('extend_remove.' . $extend->key)) {
				if(isset($extend->value->filename) and strlen($extend->value->filename)) {
					Query::table(static::table($extend->type . '_meta'))
						->where('extend', '=', $extend->id)
						->where($extend->type, '=', $item)->delete();

					$resource = PATH . 'content' . DS . $extend->value->filename;
					file_exists($resource) and unlink(PATH . 'content' . DS . $extend->value->filename);
				}
			}
		}
	}

}
