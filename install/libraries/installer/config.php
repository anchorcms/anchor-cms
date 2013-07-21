<?php namespace Installer;

use Braces;

class Config {

	private $session;

	public function __construct($session, $support) {
		$this->session = $session;
		$this->support = $support;
	}

	public function write($file) {
		switch($file) {
			case 'app':
				$this->app();
				break;
			case 'db':
				$this->db();
				break;
		}
	}

	private function app() {
		$distro = Braces::compile(APP . 'storage/application.distro.php', array(
			'url' => $this->session['metadata']['site_path'],
			'index' => ($this->support->has_mod_rewrite() ? '' : 'index.php'),
			'key' => noise(),
			'language' => $this->session['i18n']['language'],
			'timezone' => $this->session['i18n']['timezone']
		));

		file_put_contents(PATH . 'anchor/config/app.php', $distro);
	}

	private function db() {
		$distro = Braces::compile(APP . 'storage/database.distro.php', array(
			'hostname' => $this->session['database']['host'],
			'port' => $this->session['database']['port'],
			'username' => $this->session['database']['user'],
			// if the password contains single quotes, escape them
			'password' => str_replace("'", "\'", $this->session['database']['pass']),
			'database' => $this->session['database']['name'],
			'prefix' => $this->session['database']['prefix']
		));

		file_put_contents(PATH . 'anchor/config/db.php', $distro);
	}

}