<?php defined('IN_CMS') or die('No direct access allowed.');

class Html {
	
	public static function encode($str) {
		return htmlentities($str, ENT_NOQUOTES, "UTF-8");
	}

	public static function decode($str) {
		return html_entity_decode($str, ENT_NOQUOTES, "UTF-8");
	}

}