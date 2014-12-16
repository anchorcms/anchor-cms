<?php

return array(

	'extend' => 'Breid uit',

	'fields' => 'Aangepaste velden',
	'fields_desc' => 'Voeg extra velden toe',

	'variables' => 'Site Variabelen',
	'variables_desc' => 'Voeg extra metadata toe',

	'create_field' => 'Maak nieuw veld',
	'editing_custom_field' => 'Veld &ldquo;%s&rdquo; bewerken',
	'nofields_desc' => 'Nog geen velden',

	'create_variable' => 'Maak nieuwe variabele',
	'editing_variable' => 'Variabele &ldquo;%s&rdquo; bewerken',
	'novars_desc' => 'Nog geen variabelen',

	// form fields
	'type' => 'Type',
	'type_explain' => 'Het type content waar u dit veld aan wilt toevoegen.',

	'field' => 'Veld',
	'field_explain' => 'Html invul type',

	'key' => 'Unieke sleutel',
	'key_explain' => 'De unieke sleutel voor uw veld',
	'key_missing' => 'Vul alstublieft een unieke sleutel in',
	'key_exists' => 'Deze sleutel is al in gebruik',

	'label' => 'Etiket',
	'label_explain' => 'Menselijk leesbare naam voor uw veld',
	'label_missing' => 'Voeg alstublieft een etiket toe',

	'attribute_type' => 'Bestandstypes ',
	'attribute_type_explain' => 'Met comma gescheiden lijst van geaccepteerde bestandstypes. Laat leeg om alle te accepteren.',
	'attribute_type_explain' => 'Comma separated list of accepted file types, empty to accept all.',

	// images
	'attributes_size_width' => 'Maximale breedte afbeeldingen',
	'attributes_size_width_explain' => 'Afbeeldingen zullen autmatisch worden verkleind als de maximale breedte wordt overschreden.',

	'attributes_size_height' => 'Maximale hoogte afbeeldingen',
	'attributes_size_height_explain' => 'Afbeeldingen zullen autmatisch worden verkleind als de maximale hoogte wordt overschreden.',

	// custom vars
	'name' => 'Naam',
	'name_explain' => 'Een unieke naam',
	'name_missing' => 'Vul alstublieft een unieke naam in',
	'name_exists' => 'Deze naam is al in gebruik',

	'value' => 'Waarde',
	'value_explain' => 'De data die u wilt opslaan (tot 64kb)',
	'value_code_snipet' => 'Snipper dat u aan uw sjabloon wilt toevoegen<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Uw variabele is aangemaakt',
	'variable_updated' => 'Uw variabele is bijgewerkt',
	'variable_deleted' => 'Uw variabele is verwijderd',

	'field_created' => 'Uw veld is aangemaakt',
	'field_updated' => 'Uw veld is bijgewerkt',
	'field_deleted' => 'Uw veld is verwijderd'

);
