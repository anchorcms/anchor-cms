<?php namespace Types;

use Type;
use Input;
use Json;
use Uploader;

class File extends Type {

	public static $name = 'File';

	public static $type = 'file';

	public function value($default = '') {
		if( ! isset($this->field->value->filename)) {
			return $default;
		}

		return asset('content/' . $this->field->value->filename);
	}

	public function html() {
		$value = isset($this->field->value->filename) ? $this->field->value->filename : '';

		$html = '<span class="current-file">';

		if($value) {
			$html .= '<a href="' . asset('content/' . $value) . '" target="_blank">' . $value . '</a>';
		}

		$html .= '</span>
			<span class="file">
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

		$uploader = new Uploader(PATH . 'content');
		$filepath = $uploader->upload($file);

		$name = $file['name'];
		$filename = basename($filepath);

		return Json::encode(compact('name', 'filename'));
	}

	public function delete() {
		$resource = PATH . 'content' . DS . $this->field->value->filename;
		file_exists($resource) and unlink(PATH . 'content' . DS . $this->field->value->filename);
	}

	public static function attributes($input) {
		return Json::encode(array(
			'attributes' => array('type' => $input['attributes']['type'])
		));
	}

}