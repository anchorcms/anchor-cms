<?php

namespace Anchorcms;

abstract class Plugin
{

    abstract public static function getSubscribedEvents(): array;
}
