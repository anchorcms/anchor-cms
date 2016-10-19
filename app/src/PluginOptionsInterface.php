<?php
namespace Anchorcms;

/**
 * Interface PluginOptionsInterface
 *
 * @package Anchorcms
 */
interface PluginOptionsInterface
{
    public function addOptions();

    public function getOptions(): array;
}
