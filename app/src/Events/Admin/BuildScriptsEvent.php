<?php
namespace Anchorcms\Events\Admin;

use Symfony\Component\EventDispatcher\Event;

class BuildScriptsEvent extends Event
{
    public function __construct()
    {
    }

    public function getScripts()
    {
        return '<script src="foo"></script>';
    }
}
