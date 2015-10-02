<?php

return array(

	'extend' => 'Laienda',

	'fields' => 'Kohandatud Väljad',
	'fields_desc' => 'Loo lisavälju',

	'variables' => 'Saidi Muutujad',
	'variables_desc' => 'Loo lisa metaandmeid',

	'create_field' => 'Loo uus väli',
	'editing_custom_field' => 'Välja muutmine &ldquo;%s&rdquo;',
	'nofields_desc' => 'Ühtegi välja pole veel',

	'create_variable' => 'Loo uus muutuja',
	'editing_variable' => 'Muutuja muutmine &ldquo;%s&rdquo;',
	'novars_desc' => 'Ühtegi muutujat pole veel¼',

	// form fields
	'type' => 'Tüüp',
	'type_explain' => 'Sisu tüüp, millele sa tahad selle välja lisada.',

	'field' => 'Väli',
	'field_explain' => 'Html sisendi tüüp',

	'key' => 'Unikaalne Võti',
	'key_explain' => 'Unikaalne võti sinu väljale',
	'key_missing' => 'Palun sisesta unikaalne võti',
	'key_exists' => 'Võti on juba kasutuses',

	'label' => 'Silt',
	'label_explain' => 'Inim-loetav nimi sinu väljale',
	'label_missing' => 'Palun sisesta silt',

	'attribute_type' => 'Failitüübid',
	'attribute_type_explain' => 'Komadega eraldatud loend aktsepteeritud failitüüpidest, tühjana aktsepteerib kõiki.',

	// images
	'attributes_size_width' => 'Pildi maksimaalne laius',
	'attributes_size_width_explain' => 'Piltide suurust muudetakse kui need on suuremad kui maksimaalsuurus.',

	'attributes_size_height' => 'Pildi maksimaalne kõrgus',
	'attributes_size_height_explain' => 'Piltide suurust muudetakse kui need on suuremad kui maksimaalsuurus.',

	// custom vars
	'name' => 'Nimi',
	'name_explain' => 'Unikaalne nimi',
	'name_missing' => 'Palun sisesta unikaalne nimi',
	'name_exists' => 'Nimi on juba kasutuses',

	'value' => 'Väärtus',
	'value_explain' => 'Andmed mida sa soovid salvestada (kuni 64 kb)',
	'value_code_snipet' => 'Koodijupp, mida lisada template\'i:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Sinu muutuja on loodud',
	'variable_updated' => 'Sinu muutuja on uuendatud',
	'variable_deleted' => 'Sinu muutuja on kustutatud',

	'field_created' => 'Sinu väli on loodud',
	'field_updated' => 'Sinu väli on uuendatud',
	'field_deleted' => 'Sinu väli on kustutatud'

);
