<?php

class Date {

	/*
	 * Format a date as per users timezone and format
	 */
	public static function format($date, $format = null) {
		// set the meta format
		if(is_null($format)) {
			$format = Config::meta('date_format', 'jS F, Y');
		}

		$date = new DateTime($date, new DateTimeZone('GMT'));
		$date->setTimezone(new DateTimeZone(Config::app('timezone')));

		return $date->format($format);
	}

	/*
	 * All database dates are stored as GMT
	 */
	public static function mysql($date) {
		$date = new DateTime($date, new DateTimeZone('GMT'));

		return $date->format('Y-m-d H:i:s');
	}

}