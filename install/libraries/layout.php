<?php

use System\view;

/**
 * layout class
 * Layouts are basically Views, plus header and footer loaded automatically
 */
class layout
{
    /**
     * Creates a new layout.
     *
     * @param string $path relative path to the view template file
     * @param array  $vars (optional) view variables to replace
     *
     * @return \System\view
     */
    public static function create($path, $vars = [])
    {
        return View::create($path, $vars)
                   ->partial('header', 'partials/header', $vars)
                   ->partial('footer', 'partials/footer', $vars);
    }
}
