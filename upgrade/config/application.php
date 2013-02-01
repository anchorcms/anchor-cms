<?php

return array(
	// Application URL
	'url' => rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'),

	// Application Index
	'index' => 'index.php',

	// Application Timezone
	'timezone' => 'Europe/London',

	// Application Key
	'key' => 'YourSecretKeyGoesHere',

	// Default Application Language
	'language' => 'en_GB',

	// Application Character Encoding
	'encoding' => 'UTF-8'
);