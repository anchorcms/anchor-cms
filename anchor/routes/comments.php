<?php

use System\config;
use System\database\query;
use System\input;
use System\route;
use System\uri;
use System\view;

Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    /**
     * List Comments
     */
    Route::get(['admin/comments', 'admin/comments/(:num)'], function ($page = 1) {
        $query   = Query::table(Base::table(Comment::$table));
        $perpage = Config::get('admin.posts_per_page');
        $count   = $query->count();
        $results = $query
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->sort('date', 'desc')
            ->get();

        $vars['comments'] = new Paginator($results, $count, $page, $perpage, Uri::to('admin/comments'));
        $vars['statuses'] = [
            ['url' => '', 'lang' => 'global.all', 'class' => 'active'],
            ['url' => 'pending', 'lang' => 'global.pending', 'class' => 'pending'],
            ['url' => 'approved', 'lang' => 'global.approved', 'class' => 'approved'],
            ['url' => 'spam', 'lang' => 'global.spam', 'class' => 'spam']
        ];

        return View::create('comments/index', $vars)
                   ->partial('header', 'partials/header')
                   ->partial('footer', 'partials/footer');
    });

    /**
     * List Comments by status
     */
    Route::get([
        'admin/comments/(pending|approved|spam)',
        'admin/comments/(pending|approved|spam)/(:num)'
    ], function ($status = '', $page = 1) {
        $query   = Query::table(Base::table(Comment::$table));
        $perpage = Config::get('admin.posts_per_page');

        if (in_array($status, ['pending', 'approved', 'spam'])) {
            $query->where('status', '=', $status);
        }

        $count   = $query->count();
        $results = $query
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->sort('date', 'desc')
            ->get();

        $vars['comments'] = new Paginator($results, $count, $page, $perpage, Uri::to('admin/comments/' . $status));
        $vars['status']   = $status;
        $vars['statuses'] = [
            ['url' => '', 'lang' => 'global.all', 'class' => ''],
            ['url' => 'pending', 'lang' => 'global.pending', 'class' => 'pending'],
            ['url' => 'approved', 'lang' => 'global.approved', 'class' => 'approved'],
            ['url' => 'spam', 'lang' => 'global.spam', 'class' => 'spam']
        ];

        return View::create('comments/index', $vars)
                   ->partial('header', 'partials/header')
                   ->partial('footer', 'partials/footer');
    });

    /**
     * Edit Comment
     */
    Route::get('admin/comments/edit/(:num)', function ($id) {
        $vars['token']    = Csrf::token();
        $vars['comment']  = Comment::find($id);
        $vars['statuses'] = [
            'approved' => __('global.approved'),
            'pending'  => __('global.pending'),
            'spam'     => __('global.spam')
        ];

        return View::create('comments/edit', $vars)
                   ->partial('header', 'partials/header')
                   ->partial('footer', 'partials/footer');
    });

    Route::post('admin/comments/edit/(:num)', function ($id) {
        $input = Input::get(['name', 'email', 'text', 'status']);

        foreach ($input as $key => &$value) {
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('name')
                  ->is_max(3, __('comments.name_missing'));

        $validator->check('text')
                  ->is_max(3, __('comments.text_missing'));

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);

            return Response::redirect('admin/comments/edit/' . $id);
        }

        Comment::update($id, $input);
        Notify::success(__('comments.updated'));

        return Response::redirect('admin/comments/' . $input['status']);
    });

    /**
     * Delete Comment
     */
    Route::get('admin/comments/delete/(:num)', function ($id) {
        $comment = Comment::find($id);
        $status  = $comment->status;

        $comment->delete();

        Notify::success(__('comments.deleted'));

        return Response::redirect('admin/comments/' . $status);
    });
});
