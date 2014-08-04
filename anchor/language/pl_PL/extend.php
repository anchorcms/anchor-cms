<?php

return array(

	'extend' => 'Rozszerz',

	'fields' => 'Własne pola',
	'fields_desc' => 'Stwórz dodatkowe pola',

	'variables' => 'Zmienne strony',
	'variables_desc' => 'Stwórz dodatkowe meta-dane',

	'create_field' => 'Stwórz nowe pole',
	'editing_custom_field' => 'Edytujesz pole &ldquo;%s&rdquo;',
	'nofields_desc' => 'Brak pól',

	'create_variable' => 'Stwórz nową zmienną',
	'editing_variable' => 'Edytujesz zmienną &ldquo;%s&rdquo;',
	'novars_desc' => 'Brak zmiennych',

	// form fields
	'type' => 'Typ',
	'type_explain' => 'Rodzaj treści, jaki chcesz dodać do tego pola',

	'field' => 'Pole',
	'field_explain' => 'Rodzaj pola HTML',

	'key' => 'Identyfikator',
	'key_explain' => 'Unikalny identyfikator pola',
	'key_missing' => 'Proszę podać unikalny identyfikator',
	'key_exists' => 'Taki identyfikator już itnieje',

	'label' => 'Etykieta',
	'label_explain' => 'Nazwa twojego pola',
	'label_missing' => 'Proszę podać etykietę',

	'attribute_type' => 'Rodzaj plików',
	'attribute_type_explain' => 'Oddzielone przecinkami rodzaje plików, puste pole oznacza akceptację wszystkich.',

	// images
	'attributes_size_width' => 'Maks. wys. obrazka',
	'attributes_size_width_explain' => 'Przesyłane obrazki będą skalowane, jeżeli będą większe niż ten rozmiar',

	'attributes_size_height' => 'Maks. wys. obrazka',
	'attributes_size_height_explain' => 'Przesyłane obrazki będą skalowane, jeżeli będą większe niż ten rozmiar',

	// custom vars
	'name' => 'Nazwa',
	'name_explain' => 'Unikalna nazwa',
	'name_missing' => 'Proszę podać unikalną nazwę',
	'name_exists' => 'Taka nazwa już istnieje',

	'value' => 'Wartość',
	'value_explain' => 'Informacje, jakie chcesz przechować (maks. 64kb)',
	'value_code_snipet' => 'Kod do wstawienia na stronę:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Zmienna została utworzona',
	'variable_updated' => 'Zmienna została zaktualizowana',
	'variable_deleted' => 'Zmienna została usunięta',

	'field_created' => 'Pole zostało stworzone',
	'field_updated' => 'Pole zostało zaktualizowane',
	'field_deleted' => 'Pole zostało usunięte'

);