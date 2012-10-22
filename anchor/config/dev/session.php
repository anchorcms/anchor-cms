<?php

return array(

	/*
		Session Driver
		The name of the session driver used by your application.
		Drivers: 'cookie', 'database'.
	*/

	'driver' => 'database',

	/*
		Session Cookie Name
		The name that should be given to the session cookie.
	*/

	'cookie' => 'anchorcms',

	/*
		Session Database
		The database table in which the session should be stored.
	*/

	'table' => 'sessions',

	/*
		Session Lifetime
		The number of minutes a session can be idle before expiring.
	*/

	'lifetime' => 14400,

	/*
		Session Expiration On Close
		Determines if the session should expire when the user's web browser closes.
	*/

	'expire_on_close' => false,

	/*
		Session Cookie Path
		The path for which the session cookie is available.
	*/

	'path' => '/',
	
	/*
		Session Cookie Domain
		The domain for which the session cookie is available.
	*/

	'domain' => '',

	/*
		HTTPS Only Session Cookie
		Determines if the cookie should only be sent over HTTPS.
	*/
	'secure' => false
);