<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Anchor Cms - Default configuration
*/
return array(
	//  MySQL database details
	'database' => array(
		'host' => 'localhost',
		'port' => '3306',
		'username' => 'root',
		'password' => '',
		'name' => 'anchorcms'
	),
	
	// Application settings
	'application' => array(
		// url paths
		'base_url' => '/',
		'index_page' => 'index.php',

		// your time zone
		'timezone' => 'UTC',

		// access to admin
		'admin_folder' => 'admin',

		// your unique application key used for signing passwords
		'key' => '',

		// Default application language
		'language' => 'en_GB'
	),
	
	// Session details
	'session' => array(
		'name' => 'anchorcms',
		'expire' => 3600,
		'path' => '/',
		'domain' => ''
	),

	// Error handling
	'error' => array(
		'ignore' => array(E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED),
		'detail' => false,
		'log' => false
	),
	
	//Foreign characters
	'foreign_characters' => array(
		'/ä|æ|ǽ/' => 'ae',
		'/ö|œ/' => 'oe',
		'/ü/' => 'ue',
		'/Ä/' => 'Ae',
		'/Ü/' => 'Ue',
		'/Ö/' => 'Oe',
		'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
		'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',
		'/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
		'/ç|ć|ĉ|ċ|č/' => 'c',
		'/Ð|Ď|Đ/' => 'D',
		'/ð|ď|đ/' => 'd',
		'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
		'/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
		'/Ĝ|Ğ|Ġ|Ģ/' => 'G',
		'/ĝ|ğ|ġ|ģ/' => 'g',
		'/Ĥ|Ħ/' => 'H',
		'/ĥ|ħ/' => 'h',
		'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' => 'I',
		'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/' => 'i',
		'/Ĵ/' => 'J',
		'/ĵ/' => 'j',
		'/Ķ/' => 'K',
		'/ķ/' => 'k',
		'/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
		'/ĺ|ļ|ľ|ŀ|ł/' => 'l',
		'/Ñ|Ń|Ņ|Ň/' => 'N',
		'/ñ|ń|ņ|ň|ŉ/' => 'n',
		'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
		'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
		'/Ŕ|Ŗ|Ř/' => 'R',
		'/ŕ|ŗ|ř/' => 'r',
		'/Ś|Ŝ|Ş|Š/' => 'S',
		'/ś|ŝ|ş|š|ſ/' => 's',
		'/Ţ|Ť|Ŧ/' => 'T',
		'/ţ|ť|ŧ/' => 't',
		'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
		'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
		'/Ý|Ÿ|Ŷ/' => 'Y',
		'/ý|ÿ|ŷ/' => 'y',
		'/Ŵ/' => 'W',
		'/ŵ/' => 'w',
		'/Ź|Ż|Ž/' => 'Z',
		'/ź|ż|ž/' => 'z',
		'/Æ|Ǽ/' => 'AE',
		'/ß/'=> 'ss',
		'/Ĳ/' => 'IJ',
		'/ĳ/' => 'ij',
		'/Œ/' => 'OE',
		'/ƒ/' => 'f'
	),

	// Show database profile
	'debug' => false
);
