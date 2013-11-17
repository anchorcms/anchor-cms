<?php

return array(

	'extend' => 'Extend',

	'fields' => 'Aangepaste velden',
	'fields_desc' => 'Maak extra velden',

	'variables' => 'Site variabelen',
	'variables_desc' => 'Maak extra metadata',

	'create_field' => 'Maak een nieuwe veld',
	'editing_custom_field' => 'Bewerk veld &ldquo;%s&rdquo;',
	'nofields_desc' => 'Nog geen velden',

	'create_variable' => 'Maak een nieuwe variabele',
	'editing_variable' => 'Bewerk variabele &ldquo;%s&rdquo;',
	'novars_desc' => 'Nog geen variabelen',

	// form fields
	'type' => 'Soort',
	'type_explain' => 'Het type van content waar dit veld op van toepassing is.',

	'field' => 'Veld',
	'field_explain' => 'Html invoer type',

	'key' => 'Unieke naam',
	'key_explain' => 'De unieke naam van je veld',
	'key_missing' => 'Voer een unieke naam in',
	'key_exists' => 'Deze naam wordt al gebruikt',

	'label' => 'Label',
	'label_explain' => 'Door mensen leesbare naam van je veld',
	'label_missing' => 'Voer een label in',

	'attribute_type' => 'Bestandstypes',
	'attribute_type_explain' => 'Door komma gescheiden lijst van geaccepteerde bestandstypes, laat leeg om alles te accepteren.',

	// images
	'attributes_size_width' => 'Maximale breedte',
	'attributes_size_width_explain' => 'Afbeeldingen worden verkleind als ze groter zijn dan de maximale grote.',

	'attributes_size_height' => 'Maximale hoogte',
	'attributes_size_height_explain' => 'Afbeeldingen worden verkleind als ze groter zijn dan de maximale grote.',

	// custom vars
	'name' => 'Naam',
	'name_explain' => 'Een unieke naam',
	'name_missing' => 'Voer een unieke naam in',
	'name_exists' => 'Deze naam wordt al gebruikt',

	'value' => 'Waarde',
	'value_explain' => 'De data die je op wilt slaan (tot 64kb)',
	'value_code_snipet' => 'Snippet om in je template te zetten:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Je variabele is gecre&#235;erd',
	'variable_updated' => 'Je variabele is geupdatet',
	'variable_deleted' => 'Je variabele is verwijderd',

	'field_created' => 'Je veld is gecre&#235;erd',
	'field_updated' => 'Je veld is geupdatet',
	'field_deleted' => 'Je veld is verwijderd'

);