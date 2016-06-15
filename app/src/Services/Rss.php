<?php

namespace Anchorcms\Services;

class Rss {

	protected $document;

	protected $channel;

	public function __construct($name, $description, $url, $language = 'en', $ttl = 60) {
		// create a dom xml object
		$this->document = new \DOMDocument('1.0', 'UTF-8');

		// create our rss feed
		$rss = $this->element('rss', null, [
			'version' => '2.0',
			'xmlns:atom' => 'http://www.w3.org/2005/Atom',
			'xmlns:content' => 'http://purl.org/rss/1.0/modules/content',
		]);
		$this->document->appendChild($rss);

		// create channel
		$this->channel = $this->element('channel');
		$rss->appendChild($this->channel);

		// title
		$title = $this->element('title', $name);
		$this->channel->appendChild($title);

		// link
		$link = $this->element('link', $url);
		$this->channel->appendChild($link);

		// description
		$description = $this->element('description', $description);
		$this->channel->appendChild($description);

		// language
		// http://www.rssboard.org/rss-language-codes
		$language = $this->element('language', $language);
		$this->channel->appendChild($language);

		$ttl = $this->element('ttl', $ttl);
		$this->channel->appendChild($ttl);

		$docs = $this->element('docs', 'http://blogs.law.harvard.edu/tech/rss');
		$this->channel->appendChild($docs);

		$copyright = $this->element('copyright', $name);
		$this->channel->appendChild($copyright);

		// atom self link
		$atom = $this->element('atom:link', null, array(
			'href' => $url,
			'rel' => 'self',
			'type' => 'application/rss+xml'
		));
		$this->channel->appendChild($atom);
	}

	protected function element($name, $value = null, $attributes = array()) {
		$element = $this->document->createElement($name);

		if(strip_tags($value) != $value) {
			$node = new \DOMCdataSection($value);
		}
		else {
			$node = new \DOMText($value);
		}

		$element->appendChild($node);

		foreach($attributes as $key => $val) {
			$element->setAttribute($key, $val);
		}

		return $element;
	}

	protected function itemTitle($str, $item) {
		$element = $this->element('title', $str);
		$item->appendChild($element);
	}

	protected function itemLink($str, $item) {
		$element = $this->element('guid', $str);
		$item->appendChild($element);
	}

	protected function itemDesc($str, $item) {
		$element = $this->element('description', $str);
		$item->appendChild($element);
	}

	protected function itemDate(\DateTime $date, $item) {
		$element = $this->element('pubDate', $date->format(\DateTime::RSS));
		$item->appendChild($element);
	}

	protected function itemCategory(array $category, $item) {
		list($url, $name) = $category;

		$element = $this->element('category', $name, ['domain' => $url]);
		$item->appendChild($element);
	}

	protected function itemCategories(array $categories, $item) {
		foreach($categories as $category) {
			$this->itemCategory($category, $item);
		}
	}

	protected function itemAuthor(array $author, $item) {
		list($email, $name) = $author;

		$element = $this->element('author', sprintf('%s (%s)', $email, $name));
		$item->appendChild($element);
	}

	protected function itemAttachments(array $attachments, $item) {
		foreach($attachments as $attachment) {
			$element = $this->element('enclosure', null, $attachment);
			$item->appendChild($element);
		}
	}

	protected function itemContent($content, $item) {
		$element = $this->element('content:encoded', $content);
		$item->appendChild($element);
	}

	public function item(array $params) {
		$item = $this->element('item');
		$this->channel->appendChild($item);

		foreach($params as $key => $value) {
			$method = 'item' . ucfirst($key);
			$this->$method($value, $item);
		}
	}

	public function output() {
		// dump xml tree
		return $this->document->saveXML();
	}

}
