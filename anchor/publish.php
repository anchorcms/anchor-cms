<?php

require __DIR__ . '/autoload.php';

/**
 * Publish Anchor assets to the public folder
 */
$public = __DIR__ . '/../public/vendor/anchorcms/anchor-core';

if( ! is_dir($public)) mkdir($public, 0701, true);

$core = realpath(__DIR__ . '/../../anchor-core/public');

$l = new lessc;
$l->setFormatter('compressed');

$assets = new Anchor\Services\Assets($l);
$assets->copy($core, $public);
$assets->compile($public);