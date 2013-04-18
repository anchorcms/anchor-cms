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

			$context .= $num . ': ' . $this->highlight($text) . '</pre>';
		}

		return $context;
	}

	/**
	 * Output pretty html
	 */
	public function response() {
		$file = substr($this->exception->getFile(), strlen(PATH));

		echo '<html>
			<head>
				<title>Uncaught Exception</title>
				<style>
					body{
						font: 15px/25px "Open Sans", arial, sans-serif;
						margin: 2em
					}

					.exception {
						border-radius: 6px;
						background: #2f2f2f;
						color: #8ac6f2;
						margin-bottom: 2em;
						padding: 1em;
						word-wrap: break-word;
					}

						.exception .source {
							font-size: 13px;
						}

					.trace {
						background: #F5F5F5;
						margin-bottom: 2em;
						color: #412227;
					}

						.trace pre {
							border-bottom: 2px solid #E0E0FF;
							padding: 8px 1em;
							margin: 0;
						}

						.trace pre:last-child {
							border-bottom: none;
						}

					.context {
						background: #2f2f2f;
						color: #f6f3e8;
					}

						.context pre:nth-child(odd) {
							background: #2a2a2a;
						}

						.context pre {
							margin: 0;
							padding: 2px 6px;
							display: block;
						}

						.context pre.highlight {
							background: #222222;
						}

						.context .string {
							color: #D1E751;
						}

						.context .keyword {
							color: #fc7883;
						}
				</style>
			</head>
			<body>
				<div class="exception">
					' . $this->exception->getMessage() . '<br>
					<span class="source">
						<em>' . $file . '</em>
						:
						<strong>' . $this->exception->getLine() . '</strong>
					</span>
				</div>
				<div class="trace">
				' . $this->trace() . '
				</div>
				<div class="context">
				' . $this->context() . '
				</div>
			</body>
			</html>';
	}

}