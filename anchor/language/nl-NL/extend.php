<?php

return array(

	'extend' => 'Uitbreiden',

	'fields' => 'Aangepaste velden',
	'fields_desc' => 'Maak extra velden',

	'variables' => 'Site Variabelen',
	'variables_desc' => 'Maak extra metadata',

	'create_field' => 'Maak een nieuw veld',
	'editing_custom_field' => 'Bewerk veld &ldquo;%s&rdquo;',
	'nofields_desc' => 'Nog geen velden',

	'create_variable' => 'Maak een nieuwe variabele',
	'editing_variable' => 'Bewerk variabelen &ldquo;%s&rdquo;',
	'novars_desc' => 'Nog geen variabelen',

	// form fields
	'type' => 'Type',
	'type_explain' => 'Het type inhoud dat u aan dit veld wilt toevoegen.',

	'field' => 'Veld',
	'field_explain' => 'Html invoer type',

	'key' => 'Unieke key',
	'key_explain' => 'De unieke key voor uw veld',
	'key_missing' => 'Gelieve een unieke key invoeren',
	'key_exists' => 'Key is al in gebruik',

	'label' => 'Label',
	'label_explain' => 'Door mensen leesbare naam voor uw veld,
	'label_missing' => 'Gelieve een label toevoegen',

	'attribute_type' => 'Bestandstypen',
	'attribute_type_explain' => 'Een met comma gescheiden lijst van accepteerbare bestandstypen, laat het veld leeg om alle soorten bestanden te accepteren.',

	// images
	'attributes_size_width' => 'Maximale afbeeldings breedte',
	'attributes_size_width_explain' => 'Afbeeldingen zullen worden aangepast als ze groter zijn dan de maximale grootte',

	'attributes_size_height' => 'Maximale afbeeldings hoogte',
	'attributes_size_height_explain' => 'Afbeeldingen zullen worden aangepast als ze groter zijn dan de maximale grootte',

	// custom vars
	'name' => 'Naam',
	'name_explain' => 'Een unieke naam',
	'name_missing' => 'Voer gelieve een unieke naam in',
	'name_exists' => 'Naam is al in gebruik',

	'value' => 'Waarde',
	'value_explain' => 'De data die u wilt opslaan (maximaal 64 kb)',
	'value_code_snipet' => 'Fragment die u wilt invoegen in uw template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Uw variabele is gecreëerd',
	'variable_updated' => 'Uw variabele is bijgewerkt',
	'variable_deleted' => 'Uw variabele is verwijderd',

	'field_created' => 'Uw veld is gecreëerd',
	'field_updated' => 'Uw veld is bijgewerkt',
	'field_deleted' => 'Uw veld is verwijderd'

);
