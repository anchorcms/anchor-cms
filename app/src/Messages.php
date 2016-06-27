<?php

namespace Anchorcms;

use Anchorcms\Session\StashInterface;

class Messages
{
    protected $session;

    protected $messages;

    public function __construct(StashInterface $session)
    {
        $this->session = $session;
        $this->messages = [];
    }

    public function get(): array
    {
        return $this->session->getStash('_messages', []);
    }

    public function notice(array $messages)
    {
        $this->messages['notice'] = $messages;
        $this->session->putStash('_messages', $this->messages);
    }

    public function success(array $messages)
    {
        $this->messages['success'] = $messages;
        $this->session->putStash('_messages', $this->messages);
    }

    public function warning(array $messages)
    {
        $this->messages['warning'] = $messages;
        $this->session->putStash('_messages', $this->messages);
    }

    public function error(array $messages)
    {
        $this->messages['error'] = $messages;
        $this->session->putStash('_messages', $this->messages);
    }
}
