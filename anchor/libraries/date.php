<?php

class Date {

	public static function format($date, $format = null) {
		if(is_null($format)) $format = Config::get('meta.date_format');

		$time = is_numeric($date) ? $date : strtotime($date);

		return date($format, $time);
	}

}