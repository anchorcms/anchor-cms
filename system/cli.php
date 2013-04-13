<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

class Cli {

	protected static $args = array();

	protected static $foreground_colors = array(
		'black' => '0;30',
		'dark_gray' => '1;30',
		'blue' => '0;34',
		'dark_blue' => '1;34',
		'light_blue' => '1;34',
		'green' => '0;32',
		'light_green' => '1;32',
		'cyan' => '0;36',
		'light_cyan' => '1;36',
		'red' => '0;31',
		'light_red' => '1;31',
		'purple' => '0;35',
		'light_purple' => '1;35',
		'light_yellow' => '0;33',
		'yellow' => '1;33',
		'light_gray' => '0;37',
		'white' => '1;37',
	);

	protected static $background_colors = array(
		'black' => '40',
		'red' => '41',
		'green' => '42',
		'yellow' => '43',
		'blue' => '44',
		'magenta' => '45',
		'cyan' => '46',
		'light_gray' => '47',
	);

	private static function readline_support() {
		// Readline is an extension for PHP that makes interactive with PHP much more bash-like
		// http://www.php.net/manual/en/readline.installation.php
		return extension_loaded('readline');
	}

	// Returns the option with the given name.	You can also give the option number.
	public static function option($name, $default = false) {
		if(empty(static::$args)) {
			$args = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();

			foreach($args as $arg) {
				if(strpos($arg, '=') !== false) {
					list($key, $val) = explode('=', $arg);
					static::$args[$key] = $val;
				} else {
					static::$args[$arg] = true;
				}
			}
		}

		if(isset(static::$args[$name])) {
			return static::$args[$name];
		}

		return $default;
	}

	// Get input from the shell, using readline or the standard STDIN
	public static function input($prefix = '') {
		if(static::readline_support()) {
			return readline($prefix);
		}

		return fgets(STDIN);
	}

	// Outputs a string to the cli.	 If you send an array it will implode them with a line break.
	public static function write($text = '', $foreground = null, $background = null) {
		if(is_array($text)) {
			$text = implode(PHP_EOL, $text);
		}

		if($foreground or $background) {
			$text = static::color($text, $foreground, $background);
		}

		fwrite(STDOUT, $text.PHP_EOL);
	}

	// Outputs an error to the CLI using STDERR instead of STDOUT
	public static function error($text = '', $foreground = 'light_red', $background = null) {
		if (is_array($text)) {
			$text = implode(PHP_EOL, $text);
		}

		if ($foreground OR $background) {
			$text = static::color($text, $foreground, $background);
		}

		fwrite(STDERR, $text.PHP_EOL);
	}

	// Beeps a certain number of times.
	public static function beep($num = 1) {
		echo str_repeat("\x07", $num);
	}

	// Waits a certain number of seconds, optionally showing a wait message and waiting for a key press.
	public static function wait($seconds = 0, $countdown = false) {
		if ($countdown === true) {
			$time = $seconds;

			while ($time > 0) {
				fwrite(STDOUT, $time.'... ');
				sleep(1);
				$time--;
			}
			static::write();
		}

		else {
			if ($seconds > 0) {
				sleep($seconds);
			} else {
				static::write(static::$wait_msg);
				static::read();
			}
		}
	}

	// if operating system === windows
	public static function is_windows() {
		return 'win' === strtolower(substr(php_uname("s"), 0, 3));
	}

	// Enter a number of empty lines
	public static function new_line($num = 1) {
		// Do it once or more, write with empty string gives us a new line
		for($i = 0; $i < $num; $i++) {
			static::write();
		}
	}

	// Clears the screen of output
	public static function clear_screen() {
		static::is_windows()

			// Windows is a bit crap at this, but their terminal is tiny so shove this in
			? static::new_line(40)

			// Anything with a flair of Unix will handle these magic characters
			: fwrite(STDOUT, chr(27)."[H".chr(27)."[2J");
	}

	// Returns the given text with the correct color codes for a foreground and optionally a background color.
	public static function color($text, $foreground, $background = null) {
		if(static::is_windows()) {
			return $text;
		}

		if(!array_key_exists($foreground, static::$foreground_colors)) {
			throw new ErrorException('Invalid CLI foreground color: '.$foreground);
		}

		if($background !== null and ! array_key_exists($background, static::$background_colors)) {
			throw new ErrorException('Invalid CLI background color: '.$background);
		}

		$string = "\033[".static::$foreground_colors[$foreground]."m";

		if($background !== null) {
			$string .= "\033[".static::$background_colors[$background]."m";
		}

		$string .= $text."\033[0m";

		return $string;
	}

	// Spawn Background Process
	public static function spawn($call, $output = '/dev/null') {
		// Windows
		if(static::is_windows()) {
			pclose(popen('start /b ' . $call, 'r'));
		}
		// Some sort of UNIX
		else  {
			pclose(popen($call . ' > ' . $output . ' &', 'r'));
		}
	}

}