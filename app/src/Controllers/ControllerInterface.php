<?php

namespace Anchorcms\Controllers;

use Pimple\Container;

interface ControllerInterface
{

    public function setContainer(Container $container);
}
