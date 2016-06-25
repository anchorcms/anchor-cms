<?php

namespace Anchorcms;

use Anchorcms\Session\StashInterface;

class Messages
{

    protected $session;

    protected $messages = [];

    public function __construct(StashInterface $session)
    {
        $this->session = $session;
    }

    public function get()
    {
        return $this->session->getStash('_messages', []);
    }

    public function add($message, $group)
    {
        if (false === array_key_exists($group, $this->messages)) {
            $this->messages[$group] = [];
        }

        if (false === is_array($message)) {
            $message = [$message];
        }

        $this->messages[$group] = array_merge($this->messages[$group], $message);

        $this->session->putStash('_messages', $this->messages);
    }

    public function __call($method, $args)
    {
        $groups = ['notice', 'success', 'warning', 'error'];

        if (false === in_array($method, $groups)) {
            throw new \RuntimeException(sprintf('Call to undefined method "%s"', $method));
        }

        if (empty($args)) {
            throw new \InvalidArgumentException('You must provide a string or array of messages as the first argument.');
        }

        $this->add(array_shift($args), $method);
    }
}
