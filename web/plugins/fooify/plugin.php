<?php

namespace Anchorcms;

class Fooify extends Plugin
{
    /**
     *
     * @static
     * @access public
     *
     * @return array
     */
    public static function getSubscribedEvents() {
        return ['foo'];
    }


}
