<?php

require __DIR__ . '/autoload.php';

$l = new lessc;
$l->setFormatter('compressed');

$assets = new Anchor\Services\Assets($l);

$packages = array('anchorcms/anchor-core');

foreach($packages as $package) {
	$src = dirname(__DIR__) . '/vendor/'.$package.'/public';
	$dest = dirname(__DIR__) . '/public/vendor/'.$package;

	if( ! is_dir($dest)) {
		mkdir($dest, 0755, true);
	}

	$assets->copy($src, $dest);
	$assets->compile($dest);
}