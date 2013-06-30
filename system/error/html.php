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
use System\Response;

class Html extends Message {

	/**
	 * Highlight code snippet
	 *
	 * @param string
	 * @return string
	 */
	private function highlight($string) {
		$patterns = array(
			'#("[^"]+")#' => '<string>$1</string>',
			'#(\'[^\']+\')#' => '<string>$1</string>',
			'#(\(|\)|\[|\]|\{|\})#' => '<brace>$1</brace>',
			'#\b((a(bstract|nd|rray|s))|(c(a(llable|se|tch)|l(ass|one)|on(st|tinue)))|(d(e(clare|fault)|ie|o))|(e(cho|lse(if)?|mpty|nd(declare|for(each)?|if|switch|while)|val|x(it|tends)))|(f(inal|or(each)?|unction))|(g(lobal|oto))|(i(f|mplements|n(clude(_once)?|st(anceof|eadof)|terface)|sset))|(n(amespace|ew))|(p(r(i(nt|vate)|otected)|ublic))|(re(quire(_once)?|turn))|(s(tatic|witch))|(t(hrow|r(ait|y)))|(u(nset|se))|(__halt_compiler|break|list|(x)?or|var|while))\b#' => '<keyword>$1</keyword>'
		);

		$string = preg_replace(array_keys($patterns), array_values($patterns), e($string));

		$string = preg_replace('#(<(keyword|string|brace)>)#', '<span class="$2">', $string);

		return preg_replace('#(</(keyword|string|brace)>)#', '</span>', $string);
	}

	/**
	 * Create a pretty stack trace
	 *
	 * @return string
	 */
	private function frames() {
		$frames = '';

		foreach($this->exception->getTrace() as $frame) {
			$frames .= $this->context($frame);
		}

		return $frames;
	}

	/**
	 * Get the context from a frame
	 *
	 * @param int
	 * @return string
	 */
	private function context($frame) {
		if( ! isset($frame['file'])) {
			$html = file_get_contents(SYS . 'error/html/frame.html');
			$vars = array(
				'{{file}}' => 'internal',
				'{{line}}' => '0',
				'{{context}}' => '<pre>' . $frame['function'] . '</pre>'
			);

			return str_replace(array_keys($vars), array_values($vars), $html);
		}

		$padding = 6;
		$lines = file($frame['file']);
		$total = count($lines);
		$file = substr($frame['file'], strlen(PATH));
		$line = $frame['line'];

		$start = ($line > $padding) ? $line - $padding : 0;
		$end = (($line + $padding) > $total) ? $total : $line + $padding;

		$context = '';

		foreach(array_slice($lines, $start, $end - $start) as $index => $text) {
			$num = $line + (($index + 1) - $padding);

			if($num == $line) $context .= '<pre class="highlight">';
			else $context .= '<pre>';

			$context .= sprintf(
				'<span class="num">%s</span> %s</pre>',
				str_pad(' ', 4 - strlen($num)) . $num,
				$this->highlight($text)
			);
		}

		$html = file_get_contents(SYS . 'error/html/frame.html');
		$vars = array(
			'{{file}}' => $file,
			'{{line}}' => $line,
			'{{context}}' => $context,
			'{{trigger}}' => ($frame['file'] == $this->exception->getFile()) ? 'trigger' : ''
		);

		return str_replace(array_keys($vars), array_values($vars), $html);
	}

	/**
	 * Output pretty html
	 */
	public function response() {
		if($this->detailed) {
			$html = file_get_contents(SYS . 'error/html/body.html');
			$file = substr($this->exception->getFile(), strlen(PATH));

			$vars = array(
				'{{styles}}' => file_get_contents(SYS . 'error/html/styles.css'),
				'{{message}}' => $this->exception->getMessage(),
				'{{file}}' => $file,
				'{{line}}' => $this->exception->getLine(),
				'{{frames}}' => $this->frames(),
			);

			$html = str_replace(array_keys($vars), array_values($vars), $html);

			Response::create($html, 500)->send();
		}
		else {
			Response::error(500)->send();
		}
	}

}