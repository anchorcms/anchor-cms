<?php

return array(

	'extend' => 'Erweiterung',

	'fields' => 'Benutzerdefinierte Felder',
	'fields_desc' => 'Zusätzliches Feld erstellen',

	'variables' => 'Homepage Variablen',
	'variables_desc' => 'Benutzerdefinierte Metadaten erstellen',

	'create_field' => 'Neues Feld erstellen',
	'editing_custom_field' => 'Bearbeitung des Felds &ldquo;%s&rdquo;',
	'nofields_desc' => 'Kein Feld vorhanden',

	'create_variable' => 'Neue Variable erstellen',
	'editing_variable' => 'Bearbeitung der Variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Keine Variable vorhanden',

	// form fields
	'type' => 'Typ',
	'type_explain' => 'Der Typ des Inhalts welcher zu diesem Feld hinzugefügt werden soll.',

	'field' => 'Feld',
	'field_explain' => '',//Html Eingabe Typ

	'key' => 'Schlüsselwort',
	'key_explain' => 'Das Schlüsselwort für dein Feld',
	'key_missing' => 'Bitte gib ein einzigartiges Schlüsselwort ein',
	'key_exists' => 'Schlüsselwort ist bereits vorhanden',

	'label' => 'Label',
	'label_explain' => 'Lesbaren Namen für das Feld eingeben',
	'label_missing' => 'Bitte ein Label eingeben',

	'attribute_type' => 'Feld Typen',
	'attribute_type_explain' => 'Dateitypen die akzeptiert werden sollen mit Komma trennen, wird nichts angegeben werden alle Typen akzeptiert.',

	// images
	'attributes_size_width' => 'Maximale Bildbreite',
	'attributes_size_width_explain' => 'Bilder die größer als die maximale Breite sind, werden verkleinert.',

	'attributes_size_height' => 'Maximale Bildhöhe',
	'attributes_size_height_explain' => 'Bilder die größer als die maximale Höhe sind, werden verkleinert.',

	// custom vars
	'name' => 'Name',
	'name_explain' => 'Ein einzigartiger Name',
	'name_missing' => 'Bitte einen einzigartigen Namen eingeben',
	'name_exists' => 'Name ist bereits vohanden',

	'value' => 'Wert',
	'value_explain' => 'Die Daten, die Sie speichern möchten (bis zu 64 KB)',
	'value_code_snipet' => 'Schnipsel für das Einfügen in das Template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Deine Variable wurde erstellt',
	'variable_updated' => 'Deine Variable wurde aktualisiert',
	'variable_deleted' => 'Deine Variable wurde gelöscht',

	'field_created' => 'Dein Feld wurde erstellt',
	'field_updated' => 'Dein Feld wurde aktualisiert',
	'field_deleted' => 'Dein Feld wurde gelöscht'

);