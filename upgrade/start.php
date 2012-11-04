<?php

if( ! is_writable(PATH . 'upgrade/storage')) {
	echo View::make('halt', array('errors' => array('Please make the <code>upgrade/storage</code> directory writable.')))
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');

	exit(1);
}