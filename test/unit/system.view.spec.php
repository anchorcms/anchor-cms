<?php

use System\view;

/**
 * Mock translation function
 *
 * @return void
 */
function __()
{
}

/**
 * Mock Auth class
 */
class Auth
{
    public static function guest()
    {
    }

    public static function user()
    {

    }
}

/**
 * Mock Notify class
 */
class Notify
{
    public static function read()
    {

    }
}

describe('view', function () {
    it('should create an instance', function () {
        expect(new view('intro'))
            ->to->be->an->instanceof(view::class);
    });

    it('should create an instance using the shorthand', function () {
        /** @var view $view */
        $view = view::create('intro');

        expect($view)
            ->to->be->an->instanceof(view::class);
    });

    it('should create a view with vars', function () {
        /** @var view $view */
        $view = view::create('intro', [
            'foo' => 'bar'
        ]);

        expect($view)
            ->to->have->property('vars')
            ->that->loosely->equal([
                'foo' => 'bar'
            ]);
    });

    it('should create a view using the path shorthand', function () {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var view $view */
        $view = view::intro([
            'xyz' => 'test'
        ]);

        expect($view)
            ->to->have->property('vars')
            ->that->loosely->equal([
                'xyz' => 'test'
            ]);

        expect($view)
            ->to->have->property('path')
            ->that->equal(APP . 'views/intro' . EXT);
    });

    it('should render a view', function () {
        /** @var view $view */
        $view = new view('intro');

        $renderedView = $view->render();

        expect(preg_replace('/\s+/', '', $renderedView))
            ->to->equal(preg_replace('/\s+/', '', '<!doctype html>
     <html>
     	<head>
     		<meta charset="utf-8">
     		<title></title>
     
     		<style>
     			body {
     				font: 100% "Helvetica Neue", "Open Sans", "DejaVu Sans", "Arial", sans-serif;
     				text-align: center;
     				background: #444f5f;
     				color: #fff;
     			}
     			div {
     				width: 300px;
     				position: absolute;
     				left: 50%;
     				top: 30%;
     				margin: -80px 0 0 -150px;
     			}
     			h1 {
     				font-size: 29px;
     				line-height: 35px;
     				font-weight: 300;
     				margin: 30px 0;
     			}
     			a {
     				display: inline-block;
     				padding: 0 22px;
     				background: #2F3744;
     				color: #96A4BB;
     				font-size: 13px;
     				line-height: 38px;
     				font-weight: bold;
     				text-decoration: none;
     				border-radius: 5px;
     			}
     			@media (max-width: 300px) {
     				div {
     					width: 128px;
     					margin-left: -64px;
     				}
     				h1 {
     					font-size: 12px;
     					line-height: 14px;
     				}
     				a {
     					padding: 0 10px;
     					font-size: 10px;
     				}
     			}
     		</style>
     	</head>
     	<body>
     		<div>
     			<img src="./anchor/views/assets/img/logo.png" alt="Anchor logo">
     			<h1></h1>
     			<a href="./install/index.php"></a>
     		</div>
     
     		<script>
     			(function(d) {
     				var v = new Date().getTimezoneOffset();
     				d.cookie = \'anchor-install-timezone=\' + v + \'; path=/\';
     			}(document));
     		</script>
     	</body>
     </html>'));
    });

    it('should render a view with partials', function () {
        /** @var view $parent */
        $parent = view::create('panel');
        $parent->partial('header', 'partials/header');
        $parent->partial('footer', 'partials/footer');

        expect(preg_replace('/\s+/', '', $parent->render()))
            ->to->equal(preg_replace('/\s+/', '', '<!doctype html>
     <html lang="en">
        <head>
                <meta charset="utf-8">
                <title> </title>
                <link rel="shortcut icon" type="image/png" href="/anchor/anchor/views/assets/img/favicon.png" />
     
                <script src="/anchor/anchor/views/assets/js/zepto.js"></script>
     
                <link rel="stylesheet" href="/anchor/anchor/views/assets/css/admin.min.css">
        
                <meta http-equiv="X-UA-Compatible" content="chrome=1">
                <meta name="viewport" content="width=600">
        </head>
        <body class="admin ">
     
                
                <header class="top">
                        <div class="wrap">
                                                                <aside class="logo">
                                        <a href="/anchor/index.php?route=/admin/login">Anchor CMS</a>
                                </aside>
                                                        </div>
                </header>
     
     <header class="wrap">
        <h1></h1>
     </header>
     
     <section class="wrap">
        <h3></h3>
        </br>
        <p></p>
     </section>
     
     
                        </body>
     </html>'));
    });
});
