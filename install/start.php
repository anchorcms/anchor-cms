<?php

/*
	Pre install checks
*/

if( ! is_writable(PATH . 'anchor/config')) {
	echo View::make('')->render();

	exit(1);
}

$driver = in_array('mysql', PDO::getAvailableDrivers());

if( ! $writable  or ! $driver) {

}