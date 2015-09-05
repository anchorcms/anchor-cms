<?php

return array(

	'extend' => 'Erweiterungen',

	'fields' => 'Benutzerdefinierte Felder',
	'fields_desc' => 'Erstelle zusätzliche Eingabefelder',

	'pagetypes' => 'Benutzerdefinierte Seitentypen',
	'pagetypes_desc' => 'Erstelle zusätzliche Seitentypen',

	'variables' => 'Benutzerdefinierte Variablen',
	'variables_desc' => 'Erstelle zusätzliche Metadaten',

	'create_field' => 'Erstelle ein neues Feld',
	'editing_custom_field' => 'Bearbeite das Feld &ldquo;%s&rdquo;',
	'nofields_desc' => 'Noch keine Felder angelegt',

	'create_variable' => 'Erstelle eine neue Variable',
	'editing_variable' => 'Bearbeite die Variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Noch keine Variablen angelegt',

	'create_pagetype' => 'Erstelle einen neuen Seitentyp',
	'editing_pagetype' => 'Bearbeite den Seitentyp &ldquo;%s&rdquo;',

	// form fields
	'type' => 'Typ',
	'type_explain' => 'Die Art der Seite, für die dieses Feld verfügbar sein soll.',
	'notypes_desc' => 'Es gibt noch keine Seitentypen',

	'pagetype' => 'Seitentyp',
	'pagetype_explain' => 'Der benutzerdefinierte Seitentyp, für den dieses Feld verfügbar sein soll.',

	'field' => 'Feld',
	'field_explain' => 'Art des Inhalts',

	'key' => 'Eindeutiger Name',
	'key_explain' => 'Der eindeutige Name dieses Feldes',
	'key_missing' => 'Bitte gib einen eindeutigen Namen ein',
	'key_exists' => 'Dieses Feld existiert bereits',

	'label' => 'Feldname',
	'label_explain' => 'Angezeigter Feldname',
	'label_missing' => 'Bitte einen Feldnamen eingeben',

	'attribute_type' => 'Dateitypen',
	'attribute_type_explain' => 'Kommagetrennte Liste aller erlaubter Dateitypen (Feld leer lassen, um alle zu erlauben).',

	// images
	'attributes_size_width' => 'Maximale Bildbreite',
	'attributes_size_width_explain' => 'Bilder werden angepasst, wenn sie die maximale Bildbreite überschreiten',

	'attributes_size_height' => 'Maximale Bildhöhe',
	'attributes_size_height_explain' => 'Bilder werden angepasst, wenn sie die maximale Bildhöhe überschreiten',

	// custom vars
	'name' => 'Name',
	'name_explain' => 'Ein eindeutiger Name',
	'name_missing' => 'Bitte einen eindeutigen Namen angeben',
	'name_exists' => 'Der Name ist leider schon in Benutzung',

	'value' => 'Wert',
	'value_explain' => 'Die Daten, die diese Variable enthalten soll (maximal 64KB)',
	'value_code_snipet' => 'Code, um diese Variable einzufügen:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Deine Variable wurde erstellt',
	'variable_updated' => 'Deine Variable wurde geändert',
	'variable_deleted' => 'Deine Variable wurde gelöscht',

	'pagetype_created' => 'Dein Seitentyp wurde erstellt',
	'pagetype_updated' => 'Dein Seitentyp wurde geändert',
	'pagetype_deleted' => 'Dein Seitentyp wurde gelöscht',

	'field_created' => 'Dein Feld wurde erstellt',
	'field_updated' => 'Dein Feld wurde bearbeitet',
	'field_deleted' => 'Dein Feld wurde gelöscht'

);
