<?php namespace i18n;

use Filesystem;

class Language {

	private static $collections;

	/**
	 * Load a translation file
	 */
	public static function collection($name, $translations) {
		$collection = array();

		foreach($translations as $key => $translation) {
			$collection[$name . '.' . $key] = $translation;
		}

		return $collection;
	}

	/**
	 * Load all translation files for specified locale
	 */
	public static function translations($locale) {
		$fi = new Filesystem(APP . 'language/' . $locale, Filesystem::SKIP_DOTS);
		$translations = array();

		foreach($fi as $file) {
			if($file->isFile() and $file->getExtension() == 'php') {
				$collection = static::collection($file->getBasename('.php'), require $file->getPathname());

				$translations = array_merge($translations, $collection);
			}
		}

		return $translations;
	}

	/**
	 * Returns translations as an array for specified locale
	 */
	public static function catalogue($locale) {
		if(isset(static::$collections[$locale])) {
			return static::$collections[$locale];
		}

		return static::$collections[$locale] = static::translations($locale);
	}

}