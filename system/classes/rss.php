<?php defined('IN_CMS') or die('No direct access allowed.');

class Rss {

	private static $document;

	private static function element($name, $value = null, $attributes = array()) {
		$element = static::$document->createElement($name);
		
		if(is_null($value) === false) {
			$text = static::$document->createTextNode($value);
			$element->appendChild($text);
		}
		
		foreach($attributes as $key => $val) {
			$element->setAttribute($key, $val);
		}
		
		return $element;
	}

	public static function generate() {	
		// create a dom xml object
		static::$document = new DOMDocument('1.0', 'UTF-8');
		
		// create our rss feed
		$rss = static::element('rss', null, array('version' => '2.0', 'xmlns:atom' => 'http://www.w3.org/2005/Atom'));
		static::$document->appendChild($rss);
		
		// create channel
		$channel = static::element('channel');
		$rss->appendChild($channel);
	
			// title
			$title = static::element('title', Config::get('metadata.sitename'));
			$channel->appendChild($title);

			// link
			$url = 'http://' . $_SERVER['HTTP_HOST'];

			$link = static::element('link', $url);
			$channel->appendChild($link);

			// description
			$description = static::element('description', Config::get('metadata.description'));
			$channel->appendChild($description);

			// laguage
			// http://www.rssboard.org/rss-language-codes
			$language = static::element('language', Config::get('application.language', 'en'));
			$channel->appendChild($language);

			$ttl = static::element('ttl', 60);
			$channel->appendChild($ttl);

			$docs = static::element('docs', 'http://blogs.law.harvard.edu/tech/rss');
			$channel->appendChild($docs);

			$copyright = static::element('copyright', Config::get('metadata.sitename'));
			$channel->appendChild($copyright);

			// atom self link
			$atom = static::element('atom:link', null, array(
				'href' => $url,
				'rel' => 'self',
				'type' => 'application/rss+xml'
			));
			$channel->appendChild($atom);

		// articles
		$params = array('status' => 'published', 'sortby' => 'id', 'sortmode' => 'desc');

		foreach(Posts::list_all($params) as $post) {
			$item = static::element('item');
			$channel->appendChild($item);

				// title
				$title = static::element('title', $post->title);
				$item->appendChild($title);
		
				// link
				$url = 'http://' . $_SERVER['HTTP_HOST'] . Url::make(IoC::resolve('posts_page')->slug . '/' . $post->slug);
				$link = static::element('link', $url);
				$item->appendChild($link);
			
				// description
				$description = static::element('description', $post->description);
				$item->appendChild($description);
				
				// date
				$date = static::element('pubDate', date(DATE_RSS, $post->created));
				$item->appendChild($date);

		}

		// dump xml tree
		return static::$document->saveXML();
	}
}
