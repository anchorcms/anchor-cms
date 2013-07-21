<?php namespace System\Error;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use Exception;

abstract class Handler {

	public function __construct(Exception $exception) {
		$this->exception = $exception;
	}

	/**
	 * Returned message string
	 */
	abstract public function response();

	/**
	 * Returns array of frames provided by the error trace
	 */
	protected function frames() {
		$frames = array();

		foreach($this->exception->getTrace() as $frame) {
			if( ! isset($frame['file'])) continue;

			$frames[] = $frame;
		}

		return $frames;
	}

	/**
	 * Returns array of lines in context to frame
	 */
	protected function context($frame, $padding = 6) {
		$lines = file($frame['file']);

		$index_error = $frame['line'] - 1;
		$index_last = count($lines) - 1;

		$index_start = $index_error - $padding;
		$index_end = $index_error + $padding;

		if($index_start < 0) $index_start = 0;
		if($index_end > $index_last) $index_end = $index_last;

		$context = array();

		for($line = $index_start; $line <= $index_end; $line++) {
			$context[$line + 1] = $lines[$line];
		}

		return $context;
	}

}