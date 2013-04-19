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
use System\Error\Message;

class Html extends Message {

	/**
	 * Highlight code snippet
	 */
	private function highlight($string) {
		$patterns = array(
			'#("[^"]+")#' => '<string>$1</string>',
			'#(\'[^\']+\')#' => '<string>$1</string>',
			'#\b((a(bstract|nd|rray|s))|(c(a(llable|se|tch)|l(ass|one)|on(st|tinue)))|(d(e(clare|fault)|ie|o))|(e(cho|lse(if)?|mpty|nd(declare|for(each)?|if|switch|while)|val|x(it|tends)))|(f(inal|or(each)?|unction))|(g(lobal|oto))|(i(f|mplements|n(clude(_once)?|st(anceof|eadof)|terface)|sset))|(n(amespace|ew))|(p(r(i(nt|vate)|otected)|ublic))|(re(quire(_once)?|turn))|(s(tatic|witch))|(t(hrow|r(ait|y)))|(u(nset|se))|(__halt_compiler|break|list|(x)?or|var|while))\b#' => '<keyword>$1</keyword>'
		);

		$string = preg_replace(array_keys($patterns), array_values($patterns), e($string));

		$string = preg_replace('#(<(keyword|string)>)#', '<span class="$2">', $string);

		return preg_replace('#(</(keyword|string)>)#', '</span>', $string);
	}

	/**
	 * Create a pretty stack trace
	 */
	private function trace() {
		$trace = '';

		foreach($this->exception->getTrace() as $frame) {
			$trace .= '<pre>';

			if(isset($frame['class']) or isset($frame['function'])) {
				if(isset($frame['class'])) {
					$trace .= $frame['class'] . ' ';
				}

				if(isset($frame['function'])) {
					$trace .= '<strong>' . $frame['function'] . '</strong>';
				}
			}

			if(isset($frame['file'])) {
				$file = substr($frame['file'], strlen(PATH));
			}
			else {
				$file = '[internal function]';
			}

			$trace .= '<br><em>' . $file;

			if(isset($frame['line'])) {
				$trace .= ':<strong>' . $frame['line'] . '</strong>';
			}

			$trace .= '</em>';
			$trace .= '</pre>';
		}

		return $trace;
	}

	/**
	 * Get the context form the file
	 */
	private function context() {
		$lines = file($this->exception->getFile());
		$total = count($lines);

		$line = $this->exception->getLine();

		$start = ($line > 5) ? $line - 5 : 0;
		$end = ($line < $total - 4) ? $line + 4 : $total;

		$context = '';

		foreach(array_slice($lines, $start, $end - $start) as $index => $text) {
			$num = ($line - 4) + $index;

			if($index == 4) $context .= '<pre class="highlight">';
			else $context .= '<pre>';

			$context .= '<span class="num">' . str_pad(' ', 4 - strlen($num)) . $num . '</span> ' .
				$this->highlight($text) . '</pre>';
		}

		return $context;
	}

	/**
	 * Output pretty html
	 */
	public function response() {
		$file = substr($this->exception->getFile(), strlen(PATH));

		$html = file_get_contents(SYS . 'error/html/body.html');

		$vars = array(
			'{{styles}}' => file_get_contents(SYS . 'error/html/styles.css'),
			'{{message}}' => $this->exception->getMessage(),
			'{{file}}' => $file,
			'{{line}}' => $this->exception->getLine(),
			'{{trace}}' => $this->trace(),
			'{{context}}' => $this->context(),
		);

		echo str_replace(array_keys($vars), array_values($vars), $html);
	}

}