<?php
namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\PluginOptionsInterface;
use Anchorcms\Services\Plugins\Options as PluginOptions;

/**
 * Class Settings.
 * @package Anchorcms\Plugins\ContactFormPlugin
 */
class Options extends PluginOptions implements PluginOptionsInterface
{
    /**
     * add all contact form plugin options
     *
     * @access public
     * @return void
     */
    public function addOptions()
    {
        $this->add('foo')
            ->setType('select')
            ->setDefault('baz')
            ->addChoices([
                'bar',
                'baz'
            ]);

        $this->add('another')
            ->setType('textarea')
            ->setDefault('this is the default text.');
    }

    /**
     * return all options
     *
     * @access public
     * @return array
     */
    public function getOptions(): array
    {
        return $this->getAll();
    }
}
