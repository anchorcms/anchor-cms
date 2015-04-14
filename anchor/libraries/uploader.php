<?php

class Uploader {

	/**
	 * The path where uploaded files will be moved to
	 *
	 * @var string
	 */
	protected $destination;

	/**
	 * Holds array of valid file extensions
	 *
	 * @var array
	 */
	protected $extensions;

	/**
	 * Create a new instance of the uploader
	 *
	 * @param string
	 * @param array
	 */
	public function __construct($destination, $extensions = array()) {
		$this->set_destination($destination);
		$this->set_extensions($extensions);
	}

	/**
	 * Sets the upload destination path
	 */
	public function set_destination($path) {
		if( ! is_writable($path)) {
			throw new ErrorException('The destination path is not writable');
		}

		$this->destination = rtrim(realpath($path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Sets the upload accepted extensions
	 */
	public function set_extensions($extensions) {
		$this->extensions = array_map(function($str) {
			return strtolower($str);
		}, $extensions);
	}

	/**
	 * Translates error codes to error messages
	 *
	 * @param int
	 * @return string
	 */
	protected function get_error_message($error) {
		switch ($error) {
			case UPLOAD_ERR_INI_SIZE:
				$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
				break;
			case UPLOAD_ERR_PARTIAL:
				$message = "The uploaded file was only partially uploaded";
				break;
			case UPLOAD_ERR_NO_FILE:
				$message = "No file was uploaded";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$message = "Missing a temporary folder";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$message = "Failed to write file to disk";
				break;
			case UPLOAD_ERR_EXTENSION:
				$message = "File upload stopped by extension";
				break;
			default:
				$message = "Unknown upload error";
				break;
		}

		return $message;
	}

	/**
	 * Strips none alphanumeric and dash characters
	 *
	 * @param string
	 * @return string
	 */
	protected function format_filename($filename) {
		$dash = '-';

		// remove crazy characters
		$filename = preg_replace('#[^A-z0-9-]+#i', $dash, $filename);

		// remove repeated dashes
		$filename = preg_replace('#' . $dash . '+#', $dash, $filename);

		// trim the edges
		return trim($filename, $dash);
	}

	/**
	 * Creates a friendly file name
	 *
	 * @param string
	 * @return string
	 */
	protected function get_filename($filename) {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		$ext = strtolower($ext);

		$filename = basename($filename, '.' . $ext);

		return $this->format_filename($filename) . '.' . $ext;
	}

	/**
	 * Validates the passed $_FILES value and extension
	 *
	 * @param array
	 */
	protected function validate($file) {
		if($file['error'] !== UPLOAD_ERR_OK) {
			throw new ErrorException($this->get_error_message($file['error']));
		}

		if($file['size'] == 0) {
			throw new ErrorException('The uploaded file is empty');
		}

		if( ! is_uploaded_file($file['tmp_name'])) {
			throw new ErrorException('The uploaded file didnt not come from a valid source, possible file attack?');
		}

		if(count($this->extensions)) {
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

			if( ! in_array(strtolower($ext), $this->extensions)) {
				throw new ErrorException('File type not allowed');
			}
		}
	}

	/**
	 * Upload a file
	 *
	 * @param array $_FILES['index'] value
	 * @param string
	 * @return string
	 */
	public function upload($file, $filename = null) {
		// run validation on $_FILES input
		$this->validate($file);

		// create a nice filename
		if(is_null($filename)) {
			$filename = $this->get_filename($file['name']);
		}

		// set the final destination
		$filepath = $this->destination . $filename;

		if( ! move_uploaded_file($file['tmp_name'], $filepath)) {
			throw new ErrorException('Could not move uploaded file to destination filepath');
		}

		return $filepath;
	}

}
