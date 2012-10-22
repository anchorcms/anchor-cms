<?php

Route::get(array('/', 'install'), function() {});

Route::post('install', function() {});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});