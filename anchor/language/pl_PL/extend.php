<?php

return array(

	'extend' => 'Rozszerzenie',

	'fields' => 'Pola niestandardowe',
	'fields_desc' => 'Stwórz dodatkowe pola',

	'variables' => 'Zmienne strony',
	'variables_desc' => 'Stwórz dodatkowe metadane',

	'create_field' => 'Stwórz nowe pole',
	'editing_custom_field' => 'Edycha pola &ldquo;%s&rdquo;',
	'nofields_desc' => 'Na razie brak pól',

	'create_variable' => 'Stwórz nową zmienną',
	'editing_variable' => 'Edycja zmiennej &ldquo;%s&rdquo;',
	'novars_desc' => 'Na razie brak zmiennych',

	// form fields
	'type' => 'Typ',
	'type_explain' => 'Typ zawartości, którą bedzie przechowywać to pole.',

	'field' => 'Pole',
	'field_explain' => 'Typ pola input w HTML',

	'key' => 'Unikalny klucz',
	'key_explain' => 'Unikalny klucz twojego pola',
	'key_missing' => 'Prosze wpisać unikalny klucz',
	'key_exists' => 'Ten klucz jest już używany przez inne pole',

	'label' => 'Etykieta',
	'label_explain' => 'Etykietka widniejąca przy Twoim polu',
	'label_missing' => 'Prosze wprowadzić treść etykiety',

	'attribute_type' => 'Typy plików',
	'attribute_type_explain' => 'Oddzielona przecinkami lista akceptowanych rozszerzeń plików. Pozostaw puste aby akceptować wszystkie.',

	// images
	'attributes_size_width' => 'Maksymalna szerokość obrazka',
	'attributes_size_width_explain' => 'Obrazki zostaną przeskalowane jeśli przekroczą maksymalny rozmiar',

	'attributes_size_height' => 'Maksymalana wysokość obrazka',
	'attributes_size_height_explain' => 'Obrazki zostaną przeskalowane jeśli przekroczą maksymalny rozmiar',

	// custom vars
	'name' => 'Nazwa',
	'name_explain' => 'Unikalna nazwa zmiennej',
	'name_missing' => 'Proszę wprowadzić unikalną nazwę',
	'name_exists' => 'Nazwa jest już w użyciu',

	'value' => 'Wartość',
	'value_explain' => 'Dane które chcesz przechowywać (do 64kb)',
	'value_code_snipet' => 'Fragment kodu wstawiany do Twojego szablonu:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Twoja zmienna została utworzona',
	'variable_updated' => 'Twoja zmienna została zaktualizowana',
	'variable_deleted' => 'Twoja zmienna została usunięta',

	'field_created' => 'Twoje pole zostało utworzone',
	'field_updated' => 'Twoje pole zostało zaktualizowane',
	'field_deleted' => 'Twoje pole zostało usunięte'

);
