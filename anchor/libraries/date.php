<?php

class Date {

	public static function format($date, $format = null) {
		if(is_null($format)) $format = Config::meta('date_format', 'jS F, Y');

		$time = is_numeric($date) ? $date : strtotime($date);

		return date($format, $time);
	}

}