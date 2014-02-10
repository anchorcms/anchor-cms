<?php

require __DIR__ . '/autoload.php';

$l = new lessc;
$l->setFormatter('compressed');

$assets = new Anchor\Services\Assets($l);

$packages = array('anchorcms/anchor-core');

foreach($packages as $package) {
	$src = __DIR__ . '/../vendor/'.$package.'/public';
	$dest = __DIR__ . '/../public/vendor/'.$package;

	if( ! is_dir($dest)) {
		mkdir($dest, 701, true);
	}

	$assets->copy($src, $dest);
	$assets->compile($dest);
}