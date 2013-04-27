<?php
// nl_NL translation for AnchorCMS by www.janyksteenbeek.nl

return array(

	'extend' => 'Uitbreiden',

	'fields' => 'Aangepaste velden',
	'fields_desc' => 'Maak aangepaste velden',

	'variables' => 'Site Variabelen',
	'variables_desc' => 'Maak extra metadata',

	'create_field' => 'Maak een nieuw veld',
	'editing_custom_field' => 'Veld &ldquo;%s&rdquo; aanpassen',
	'nofields_desc' => 'Nog geen velden',

	'create_variable' => 'Maak een nieuw variabele',
	'editing_variable' => 'Variabele &ldquo;%s&rdquo; aanpassen',
	'novars_desc' => 'Nog geen variabelen',

	// form fields
	'type' => 'Type',
	'type_explain' => 'Waar wil je dit veld plaatsen?',

	'field' => 'Veld',
	'field_explain' => 'HTML invoer type',

	'key' => 'Unieke sleutel',
	'key_explain' => 'De unieke sleutel voor je veld',
	'key_missing' => 'Voer aub een unieke sleutel in',
	'key_exists' => 'Sleutel is niet uniek',

	'label' => 'Label',
	'label_explain' => 'Human-readable naam voor je veld',
	'label_missing' => 'Voer een label in',

	'attribute_type' => 'Bestand types',
	'attribute_type_explain' => 'Komma gescheiden lijst met geaccepteerde bestandstypen, leeg om alle bestandstypen te aanvaarden.',

	// images
	'attributes_size_width' => 'Maximale foto breedte',
	'attributes_size_width_explain' => 'Fotos worden automatisch verkleind als ze groter zijn dan maximum.',

	'attributes_size_height' => 'Maximale hoogte',
	'attributes_size_height_explain' => 'Fotos worden automatisch verkleind als ze groter zijn dan maximum.',

	// custom vars
	'name' => 'Naam',
	'name_explain' => 'Een unieke naam',
	'name_missing' => 'Voer aub een unieke naam in',
	'name_exists' => 'Naam is niet uniek',

	'value' => 'Waarde',
	'value_explain' => 'De data die je wilt opslaan (max 64kb)',
	'value_code_snipet' => 'Code in te voegen in uw template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Je variabele is aangemaakt',
	'variable_updated' => 'Je variabele is aangepast',
	'variable_deleted' => 'Je variabele is verwijderd',

	'field_created' => 'Je veld is aangemaakt',
	'field_updated' => 'Je veld is aangepast',
	'field_deleted' => 'Je veld is verwijderd'

);