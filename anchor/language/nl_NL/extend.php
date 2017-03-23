<?php

return array(

	'extend' => 'Uitbereiden',

	'fields' => 'Aangepaste velden',
	'fields_desc' => 'Maak aangepaste velden',

	'pagetypes' => 'Site paginatypes',
	'pagetypes_desc' => 'Maak andere paginatypes aan',

	'variables' => 'Site Variablen',
	'variables_desc' => 'Maak aangepast metadata',

	'create_field' => 'Maak een aangepast veld',
	'editing_custom_field' => 'Wijzig veld &ldquo;%s&rdquo;',
	'nofields_desc' => 'Er zijn nog geen velden',

	'create_variable' => 'Maak een nieuwe variable',
	'editing_variable' => 'Wijzig variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Er zijn nog geen variablen',

	'create_pagetype' => 'Maak een nieuw paginatype',
	'editing_pagetype' => 'Wijzig paginatype &ldquo;%s&rdquo;',

	// form fields
	'type' => 'Type',
	'type_explain' => 'The type of content you want to add this field to.',
	'type_explain' => 'Selecteer op welk type inhoud dit veld moet worden toegepast',
	'notypes_desc' => 'Er zijn nog geen pagina types',

	'pagetype' => 'Pagina Type',
	'pagetype_explain' => 'Het type pagina waarop dit veld toegepast moet worden',

	'field' => 'Veld',
	'field_explain' => 'Html input type',

	'key' => 'Unieke Sleutel',
	'key_explain' => 'De unieke sleutel voor je veld',
	'key_missing' => 'Voer een geldige sleutel in',
	'key_exists' => 'Deze sleutel is reeds in gebruik',

	'label' => 'Label',
	'label_explain' => 'Een duidelijke omschrijving van dit label',
	'label_missing' => 'Voer een label in',

	'attribute_type' => 'Bestandstype',
	'attribute_type_explain' => 'Kommagescheiden lijst met toegestaande bestandstypes, laat leeg om alles toe te staan.',

	// images
	'attributes_size_width' => 'Maximale afbeeldingbreedte',
	'attributes_size_width_explain' => 'Afbeeldingen worden aangepast wannneer de maximale grootte wordt overschreden',

	'attributes_size_height' => 'Maximale afbeeldinghoogte',
	'attributes_size_height_explain' => 'Afbeeldingen worden aangepast wannneer de maximale grootte wordt overschreden',

	// custom vars
	'name' => 'Naam',
	'name_explain' => 'Een unieke naam',
	'name_missing' => 'Voer een unieke naam in',
	'name_exists' => 'Deze naam is reeds in gebruik',

	'value' => 'Waarde',
	'value_explain' => 'Het gegeven dat je op wilt slaan. (tot maximaal 64kb)',
	'value_code_snipet' => 'Knipsel om deze variable te gebruiken in je template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Je variabel is aangemaakt',
	'variable_updated' => 'Je variabel is bijgewerkt',
	'variable_deleted' => 'Je variabel is verwijderd',

	'pagetype_created' => 'Je pagina type is aangemaakt',
	'pagetype_updated' => 'Je pagina type is bijgewerkt',
	'pagetype_deleted' => 'Je pagina type is verwijderd',

	'field_created' => 'Je veld is aangemaakt',
	'field_updated' => 'Je veld is bijgewerkt',
	'field_deleted' => 'Je veld is verwijderd'

);
