<?php

namespace Anchorcms\Services;

use DOMDocument;
use DOMElement;
use DateTime;
use Psr\Http\Message\UriInterface;

class Rss
{
    protected $document;

    protected $channel;

    public function __construct(string $name, string $description, UriInterface $url, string $language = 'en', int $ttl = 60)
    {
        // create a dom xml object
        $this->document = new DOMDocument('1.0', 'UTF-8');

        // create our rss feed
        $rss = $this->element('rss', '', [
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
        $atom = $this->element('atom:link', '', array(
            'href' => $url,
            'rel' => 'self',
            'type' => 'application/rss+xml',
        ));
        $this->channel->appendChild($atom);
    }

    protected function element(string $name, string $value = '', array $attributes = []): DOMElement
    {
        $element = $this->document->createElement($name);

        if (strip_tags($value) != $value) {
            $node = new \DOMCdataSection($value);
        } else {
            $node = new \DOMText($value);
        }

        $element->appendChild($node);

        foreach ($attributes as $key => $val) {
            $element->setAttribute($key, $val);
        }

        return $element;
    }

    protected function itemTitle(string $str, DOMElement $item)
    {
        $element = $this->element('title', $str);
        $item->appendChild($element);
    }

    protected function itemLink(string $str, DOMElement $item)
    {
        $element = $this->element('guid', $str);
        $item->appendChild($element);
    }

    protected function itemDesc(string $str, DOMElement $item)
    {
        $element = $this->element('description', $str);
        $item->appendChild($element);
    }

    protected function itemDate(DateTime $date, DOMElement $item)
    {
        $element = $this->element('pubDate', $date->format(\DateTime::RSS));
        $item->appendChild($element);
    }

    protected function itemCategory(array $category, DOMElement $item)
    {
        list($url, $name) = $category;

        $element = $this->element('category', $name, ['domain' => $url]);
        $item->appendChild($element);
    }

    protected function itemCategories(array $categories, DOMElement $item)
    {
        foreach ($categories as $category) {
            $this->itemCategory($category, $item);
        }
    }

    protected function itemAuthor(string $author, DOMElement $item)
    {
        $element = $this->element('author', $author);
        $item->appendChild($element);
    }

    protected function itemAttachments(array $attachments, DOMElement $item)
    {
        foreach ($attachments as $attachment) {
            $element = $this->element('enclosure', null, $attachment);
            $item->appendChild($element);
        }
    }

    protected function itemContent(string $content, DOMElement $item)
    {
        $element = $this->element('content:encoded', $content);
        $item->appendChild($element);
    }

    public function item(array $params)
    {
        $item = $this->element('item');
        $this->channel->appendChild($item);

        foreach ($params as $key => $value) {
            $method = 'item'.ucfirst($key);
            $this->$method($value, $item);
        }
    }

    public function output(): string
    {
        // dump xml tree
        return $this->document->saveXML();
    }
}
