<?php

Route::get(array('/', 'upgrade'), function() {});

Route::post('upgrade', function() {});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});