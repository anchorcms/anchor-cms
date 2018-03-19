<?php

use System\route;
use System\view;

Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    /**
     * List all plugins
     */
    // TODO: Unused parameter $page?
    Route::get('admin/extend/plugins', function ($page = 1) {
        $vars['token'] = Csrf::token();

        return View::create('extend/plugins/index', $vars)
                   ->partial('header', 'partials/header')
                   ->partial('footer', 'partials/footer');
    });
});
