<?php

namespace Anchorcms\Plugins\fooify;

use Anchorcms\Plugin as AnchorPlugin;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class Fooify extends AnchorPlugin
{
    public function getSubscribedEvents(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener('admin:beforeLayoutRender', [$this, 'trollOnRender']);
    }

    /**
     * replaces all content with foo, bar, or baz.
     * This, obviously, is just to mess with all of you. Lol.
     *
     * @access public
     * @param Event $event
     * @return Event
     */
    public function trollOnRender(Event $event)
    {
        $replacementWords = [
            'foo',
            'bar',
            'baz'
        ];

        $bodyWords = str_word_count($event->getVar('body'));
        $titleWords = str_word_count($event->getVar('title'));
        $title = '';

        for ($i = 0; $i < $titleWords; $i++) {
            $title .= ' ' . $replacementWords[array_rand($replacementWords, 1)];
        }

        $body = '<main class="wrap"><header class="heading"><h2>' . $title . '</h2></header>';

        for ($i = 0; $i < $bodyWords; $i++) {
            $body .= ' ' . $replacementWords[array_rand($replacementWords, 1)];
        }

        $body .= '</main';

        $event->setVar('body', $body);

        return $event;
    }
}
