<?php

require __DIR__ . '/helpers.php';
require __DIR__ . '/../vendor/autoload.php';

$app = new Pimple\Container(require __DIR__ . '/container.php');

$content = file_get_contents('http://ufosightingshotspot.blogspot.com/feeds/posts/default?alt=atom');

$doc = new \DOMDocument('1.0', 'UTF-8');
libxml_use_internal_errors(true);
$doc->loadXML($content);

$nodes = $doc->getElementsByTagName('entry');

$now = date('Y-m-d H:i:s');

$app['mappers.posts']->delete();
$app['mappers.categories']->delete();

foreach($nodes as $node) {
	// title
	$element = $node->getElementsByTagName('title')[0];

	$title = $element->nodeValue;

	// link
	$element = $node->getElementsByTagName('link')[0];

	$link = $element->getAttribute('href');

	// category
	$element = $node->getElementsByTagName('category')[1];

	$category = $element->getAttribute('term');

	// html
	$element = $node->getElementsByTagName('content')[0];

	$content = html_entity_decode($element->nodeValue);

	// search category
	$categoryId = $app['mappers.categories']->select(['id'])->where('slug', '=', $category)->column();

	if( ! $categoryId) {
		$categoryId = $app['mappers.categories']->insert([
			'title' => ucwords($category),
			'slug' => $app['slugify']->slug($category),
			'description' => '',
		]);
	}

	$slug = $app['slugify']->slug($title);
	$html = $app['markdown']->parse($content);

	$app['mappers.posts']->insert([
		'author' => 1,
		'category' => $categoryId,
		'status' => 'published',

		'created' => $now,
		'modified' => $now,
		'published' => $now,

		'title' => $title,
		'slug' => $slug,

		'content' => $content,
		'html' => $html,
	]);
}
