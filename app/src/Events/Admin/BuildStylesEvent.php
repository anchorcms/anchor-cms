<?php
namespace Anchorcms\Events\Admin;

use Symfony\Component\EventDispatcher\Event;

class BuildStylesEvent extends Event
{
    public function __construct()
    {
    }

    public function getStyles()
    {
        return '<link href="foo" type="text/css">';
    }
}
