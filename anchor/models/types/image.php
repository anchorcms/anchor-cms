<?php namespace Types;

use Type;
use Input;
use Json;
use Uploader;
use Exception;
use ErrorException;

class Image extends Type {

	public static $name = 'Image';

	public static $type = 'image';

	public function value($default = '') {
		if( ! isset($this->field->value->filename)) {
			return $default;
		}

		return asset('content/' . $this->field->value->filename);
	}

	public function html() {
		$value = $this->value();

		$html = '<span class="current-file">';

		if($value) {
			$html .= '<a href="' . $value . '" target="_blank">' . basename($value) . '</a>';
		}

		$html .= '</span>';

		$html .= '<span class="file">
			<input id="extend_' . $this->field->key . '" name="extend[' . $this->field->key . ']" type="file">
			</span>';

		if($value) {
			$html .= '</p><p>
			<label>Remove ' . $this->field->label . ':</label>
			<input type="checkbox" name="extend_remove[' . $this->field->key . ']" value="1">';
		}

		return $html;
	}

	public function save() {
		$file = array(
			'name' => $_FILES['extend']['name'][$this->field->key],
			'type' => $_FILES['extend']['type'][$this->field->key],
			'tmp_name' => $_FILES['extend']['tmp_name'][$this->field->key],
			'error' => $_FILES['extend']['error'][$this->field->key],
			'size' => $_FILES['extend']['size'][$this->field->key]
		);

		// skip empty files
		if($file['size'] == 0) return '';

		$uploader = new Uploader(PATH . 'content', array('png', 'jpg', 'bmp', 'gif'));
		$filepath = $uploader->upload($file);

		$name = $file['name'];
		$filename = basename($filepath);

		// resize when Imagick is present
		if(class_exists('\Imagick')) {
			if(isset($this->field->attributes->size->width) and
				isset($this->field->attributes->size->height)) {

				$width = intval($this->field->attributes->size->width);
				$height = intval($this->field->attributes->size->height);

				if($width > 0 and $height > 0) {
					$img = new \Imagick($filepath);
					$img->resizeImage($width, $height);
					$img->writeImage($filepath);
				}
			}
		}

		return Json::encode(compact('name', 'filename'));
	}

	public function delete() {
		$resource = PATH . 'content' . DS . $this->field->value->filename;
		file_exists($resource) and unlink(PATH . 'content' . DS . $this->field->value->filename);
	}

	public static function attributes($input) {
		return Json::encode($input['attributes']);
	}

}