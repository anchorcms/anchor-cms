<?php

use System\route;
use System\view;

Route::collection(['before' => 'auth,csrf'], function () {

    // TODO: Unused page parameter, what for?
    Route::get('admin/panel', function ($page = 1) {
        $vars['token'] = Csrf::token();

        return View::create('panel', $vars)
                   ->partial('header', 'partials/header')
                   ->partial('footer', 'partials/footer');
    });
});
