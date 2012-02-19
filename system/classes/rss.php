<?php defined('IN_CMS') or die('No direct access allowed.');

class Rss {

	private static $document;

	public static function headers() {
		// set headers
		Response::header('Content-Type', 'application/xml');
	}
	
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
		$rss = static::element('rss', null, array('version' => '2.0'));
		static::$document->appendChild($rss);
		
		// create channel
		$channel = static::element('channel');
		$rss->appendChild($channel);
		
			// title
			$title = static::element('title', Config::get('metadata.sitename'));
			$channel->appendChild($title);
		
			// link
			$link = static::element('link', 'http://' . $_SERVER['HTTP_HOST']);
			$channel->appendChild($link);
			
			// description
			$description = static::element('description', Config::get('metadata.description'));
			$channel->appendChild($description);
		
		
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
		Response::content(static::$document->saveXML());
	}
}
