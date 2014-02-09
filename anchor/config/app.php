<?php

return array(
	'url' => '/',
	'index' => 'index.php',
	'timezone' => 'UTC',
	'key' => 'ChangeMe!',
	'language' => 'en_GB',
	'encoding' => 'UTF-8',
	'providers' => array(
		'\\Ship\\Http\\Provider',
		'\\Ship\\Routing\\Provider',
		'\\Ship\\Database\\Provider',
		'\\Ship\\Database\\Query\\Provider',
		'\\Ship\\Session\\Provider',
		'\\Anchor\\Providers\\ShipServiceProvider'
	)
);