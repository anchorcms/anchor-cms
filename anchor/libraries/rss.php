<?php

/**
 * rss class
 * Provides an RSS feed generator
 */
class rss
{
    /**
     * Holds the RSS document DOM
     *
     * @var \DOMDocument
     */
    private $document;

    /**
     * Holds the RSS channel
     *
     * @var \DOMDocument|\DOMElement
     */
    private $channel;

    /**
     * Create a new RSS feed
     *
     * @param string $name        feed name
     * @param string $description feed description
     * @param string $url         feed URL
     * @param string $language    feed language code
     */
    public function __construct($name, $description, $url, $language)
    {
        // create a dom xml object
        $this->document = new DOMDocument('1.0', 'UTF-8');

        // create our rss feed
        $rss = $this->element('rss', null, [
            'version'    => '2.0',
            'xmlns:atom' => 'http://www.w3.org/2005/Atom'
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

        $ttl = $this->element('ttl', 60);
        $this->channel->appendChild($ttl);

        $docs = $this->element('docs', 'http://blogs.law.harvard.edu/tech/rss');
        $this->channel->appendChild($docs);

        $copyright = $this->element('copyright', $name);
        $this->channel->appendChild($copyright);

        // atom self link
        $atom = $this->element('atom:link', null, [
            'href' => $url,
            'rel'  => 'self',
            'type' => 'application/rss+xml'
        ]);
        $this->channel->appendChild($atom);
    }

    /**
     * Creates a new RSS DOM element
     *
     * @param string      $name       element name
     * @param string|null $value      (optional) element value
     * @param array       $attributes (optional) element attributes
     *
     * @return \DOMElement
     */
    private function element($name, $value = null, $attributes = [])
    {
        $element = $this->document->createElement($name);

        if (is_null($value) === false) {
            $text = $this->document->createTextNode($value);
            $element->appendChild($text);
        }

        foreach ($attributes as $key => $val) {
            $element->setAttribute($key, $val);
        }

        return $element;
    }

    /**
     * Appends a new item to the feed
     *
     * @param string $title       feed title
     * @param string $url         feed URL
     * @param string $description feed description
     * @param string $date        feed publication date
     *
     * @return void
     */
    public function item($title, $url, $description, $date)
    {
        $item = $this->element('item');
        $this->channel->appendChild($item);

        // title
        $title = $this->element('title', $title);
        $item->appendChild($title);

        // link
        $link = $this->element('link', $url);
        $item->appendChild($link);

        // description
        $description = $this->element('description', $description);
        $item->appendChild($description);

        // date
        $date = $this->element('pubDate', date(DATE_RSS, strtotime($date)));
        $item->appendChild($date);
    }

    /**
     * Writes the RSS XML
     *
     * @return string
     */
    public function output()
    {
        // dump xml tree
        return $this->document->saveXML();
    }
}
