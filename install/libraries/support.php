<?php

class Support {

	public static function timezones() {
		return DateTimeZone::listIdentifiers(DateTimeZone::ALL);
	}

	public static function languages() {
		$languages = array();

		$path = PATH . 'anchor/language';
		$if = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

		foreach($if as $file) {
			if($file->isDir()) {
				$languages[] = $file->getBasename();
			}
		}

		return $languages;
	}

	public static function prefered_language($default = 'en_GB') {
		if($locale = Locale::acceptFromHttp(Arr::get($_SERVER, 'HTTP_ACCEPT_LANGUAGE'))) {
			return $locale;
		}

		return $default;
	}

	public function is_apache() {
		return stripos(PHP_SAPI, 'apache') !== false;
	}

	public function is_cgi() {
		return stripos(PHP_SAPI, 'cgi') !== false;
	}

	public function has_mod_rewrite() {
		if($this->is_apache() and function_exists('apache_get_modules')) {
			return in_array('mod_rewrite', apache_get_modules());
		}

		return getenv('HTTP_MOD_REWRITE') ? true : false;
	}

	public static function is_installed() {
		return (file_exists(PATH . 'anchor/config/app.php') or file_exists(PATH . 'anchor/config/db.php'));
	}

	public static function run_checks() {
		$errors = array();

		// check installed hasnt already ran
		if(static::is_installed()) {
			$errors[] = 'Looks like Anchor is already installed!<br><em>If this is not the case remove the config files
				<code>app.php</code> and <code>db.php</code> in <code>anchor/config</code> and try again.</em>';
		}

		// web server can upload content
		if( ! is_writable(PATH . 'content')) {
			$errors[] = '<code>content</code> directory needs to be writable
				so we can upload your images and files.';
		}

		// web server can write to config dir
		if( ! is_writable(PATH . 'anchor/config')) {
			$errors[] = '<code>anchor/config</code> directory needs to be temporarily writable
				so we can create your application and database configuration files.';
		}

		// php has pdo/pdo_mysql support
		if( ! extension_loaded('PDO')) {
			$errors[] = 'Anchor requires the php module <code>PDO</code> to be installed.
				<br><em>php.net/manual/pdo.installation.php</em>';
		}

		if( ! extension_loaded('pdo_mysql')) {
			$errors[] = 'Anchor requires the php module <code>pdo_mysql</code> to be installed.
				<br><em>php.net/manual/ref.pdo-mysql.php</em>';
		}

		return $errors;
	}

}