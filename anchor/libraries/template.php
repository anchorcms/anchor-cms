<?php

use System\config;
use System\view;

/**
 * template class
 * Resolves views to the currently active theme
 */
class template extends View
{
    /** @noinspection PhpMissingParentConstructorInspection */
    /**
     * template constructor
     *
     * @param string $template template file name
     * @param array  $vars     template variables
     */
    public function __construct($template, $vars = [])
    {
        // base path
        $base = PATH . 'themes' . DS . Config::meta('theme') . DS;

        // custom article and page templates
        if ($template == 'page' or $template == 'article') {
            if ($item = Registry::get($template)) {

                if (is_readable($base . $template . '-' . $item->slug . EXT)) {

                    // check for /{{theme path}}/{{template name}}-{{item slug}}.php
                    $template .= '-' . $item->slug;
                } elseif (is_readable($base . $template . 's/' . $template . '-' . $item->slug . EXT)) {

                    // check for /{{theme path}}/{{template name plural}}-{{template name}}-{{item slug}}.php
                    $template .= 's/' . $template . '-' . $item->slug;
                } elseif (is_readable($base . $item->pagetype . EXT)) {

                    // check for /{{theme path}}/{{item type}}.php
                    $template = $item->pagetype;

                    if (is_readable($base . $item->pagetype . '-' . $item->slug . EXT)) {

                        // check for /{{theme path}}/{{item type}}-{{item slug}}.php
                        $template .= '-' . $item->slug;
                    }
                }
            }
        }

        if ($template == 'posts') {
            if ($item = Registry::get('post_category')) {
                if (is_readable($base . 'category-' . $item->slug . EXT)) {
                    $template = 'category';
                    $template .= '-' . $item->slug;
                }
            }
        }

        $this->path = $base . $template . EXT;
        $this->vars = array_merge($this->vars, $vars);
    }

    /**
     * Stringifies the template through rendering it
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Whether the template file exists
     *
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->path);
    }
}
