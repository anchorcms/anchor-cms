<?php namespace System\Error\Handlers;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use System\Error\Handler;

class Candy extends Handler {

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
	 * Output pretty html
	 */
	public function response() {
		// style frames
		$frames = '';

		$template = file_get_contents(dirname(__FILE__) . '/html/frame.html');

		$html_line = '<pre class="%s"><span class="num">%s</span> %s</pre>';

		foreach($this->frames() as $frame) {
			// style context
			$context = '';

			foreach($this->context($frame) as $num => $line) {
				$line_number = str_pad(' ', 4 - strlen($num)) . $num;
				$class_name = $num == $frame['line'] ? 'highlight' : '';

				$context .= sprintf($html_line, $class_name, $line_number, $this->highlight($line));
			}

			$vars = array('{{file}}' => $frame['file'], '{{line}}' => $frame['line'], '{{context}}' => $context);
			$frames .= str_replace(array_keys($vars), array_values($vars), $template);
		}

		$template = file_get_contents(dirname(__FILE__) . '/html/body.html');

		$vars = array(
			'{{styles}}' => file_get_contents(dirname(__FILE__) . '/html/styles.css'),
			'{{message}}' => $this->exception->getMessage(),
			'{{file}}' => $this->exception->getFile(),
			'{{line}}' => $this->exception->getLine(),
			'{{frames}}' => $frames,
		);

		$html = str_replace(array_keys($vars), array_values($vars), $template);

		if( ! headers_sent()) {
			header('Status: 500 Internal Server Error', true);
		}

		echo $html;
	}

}