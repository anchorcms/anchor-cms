<?php

return array(

	'extend' => 'Erweiterungen',

	'fields' => 'Benutzerdefinierte Felder',
	'fields_desc' => 'Erstelle zusätzliche Felder',

	'variables' => 'Seiten Variablen',
	'variables_desc' => 'Erstelle zusätzliche Metadaten',

	'create_field' => 'Erstelle ein neues Feld',
	'editing_custom_field' => 'Bearbeite das &ldquo;%s&rdquo; Feld',
	'nofields_desc' => 'Noch keine Felder',

	'create_variable' => 'Erstelle eine neue Variable',
	'editing_variable' => 'Bearbeite die &ldquo;%s&rdquo; Variable',
	'novars_desc' => 'Noch keine Variablen',

	// form fields
	'type' => 'Typ',
	'type_explain' => 'Der Inhaltstyp zu dem du das Feld hinzufügen möchtest.',

	'field' => 'Feld',
	'field_explain' => 'Html Input-Feld Typ',

	'key' => 'Unique Key',
	'key_explain' => 'Der unique key für dieses Feld',
	'key_missing' => 'Bitte gib einen unique key kein',
	'key_exists' => 'Der Key ist schon in Benutzung',

	'label' => 'Feldname',
	'label_explain' => 'Angezeigter Feldname',
	'label_missing' => 'Bitte einen Feldnamen angeben',

	'attribute_type' => 'Dateitypen',
	'attribute_type_explain' => 'Komma-separierte Liste aller erlaubter Dateitypen: Feld leer lassen um alle zu erlauben.',

	// images
	'attributes_size_width' => 'Maximale Bildbreite',
	'attributes_size_width_explain' => 'Bilder werden angepasst wenn sie die maximale Bildbreite überschreiten',

	'attributes_size_height' => 'Maximale Bildhöhe',
	'attributes_size_height_explain' => 'Bilder werden angepasst wenn sie die maximale Bildhöhe überschreiten',

	// custom vars
	'name' => 'Name',
	'name_explain' => 'Ein unique Name',
	'name_missing' => 'Bitte einen unique Name angeben',
	'name_exists' => 'Der Name ist leider schon in Benutzung',

	'value' => 'Wert',
	'value_explain' => 'Die Daten die du speichern willst (bis zu 64KB)',
	'value_code_snipet' => 'Snippet das in dein Template eingefügt werden soll:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Deine Variable wurde erstellt',
	'variable_updated' => 'Deine Variable wurde geändert',
	'variable_deleted' => 'Deine Variable wurde gelöscht',

	'field_created' => 'Dein Feld wurde erstellt',
	'field_updated' => 'Dein Feld wurde bearbeitet',
	'field_deleted' => 'Dein Feld wurde gelöscht'

);
