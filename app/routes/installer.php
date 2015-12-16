<?php

return [
	'/' => 'controllers\\installer\\install@index',
	'/l10n' => 'controllers\\installer\\install@l10n',
	'/database' => 'controllers\\installer\\install@database',
	'/metadata' => 'controllers\\installer\\install@metadata',
	'/account' => 'controllers\\installer\\install@account',
	'/complete' => 'controllers\\installer\\install@complete',
];
