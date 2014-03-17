<?php

return array(

	'extend' => 'Estendi',

	'fields' => 'Campi personalizzati',
	'fields_desc' => 'Crea nuovi campi personalizzati',

	'variables' => 'Variabili del sito',
	'variables_desc' => 'Crea nuovi metadata',

	'create_field' => 'Crea un nuovo campo',
	'editing_custom_field' => 'Modifica il campo &ldquo;%s&rdquo;',
	'nofields_desc' => 'Nessun campo aggiunto',

	'create_variable' => 'Crea una nuova variabile',
	'editing_variable' => 'Modifica la variabile &ldquo;%s&rdquo;',
	'novars_desc' => 'Nessuna variabile aggiunta',

	// form fields
	'type' => 'Tipo',
	'type_explain' => 'Il tipo del contenuto che vuoi aggiungere per questo campo.',

	'field' => 'Campo',
	'field_explain' => 'Il tipo di input HTML',

	'key' => 'Chiave unica',
	'key_explain' => 'La chiave unica per il tuo campo',
	'key_missing' => 'Perfavore inserisci una chiave unica',
	'key_exists' => 'Chiave già in uso',

	'label' => 'Etichetta',
	'label_explain' => 'Il nome del campo leggibile dalle persone',
	'label_missing' => 'Perfavore inserisci un etichetta',

	'attribute_type' => 'Tipo di File',
	'attribute_type_explain' => 'Tipi di files accettati separati da una virgola, lascia vuoto per accettarli tutti.',

	// images
	'attributes_size_width' => 'Larghezza massima immagine',
	'attributes_size_width_explain' => 'L\' immagine verrà ridimensionata se maggiore alle dimensioni stabilite',

	'attributes_size_height' => 'Altezza massima immagine',
	'attributes_size_height_explain' => 'L\' immagine verrà ridimensionata se maggiore alle dimensioni stabilite',

	// custom vars
	'name' => 'Nome',
	'name_explain' => 'Un nome univoco',
	'name_missing' => 'Perfavore inserisci un nome univoco',
	'name_exists' => 'Nome già in utilizzo',

	'value' => 'Valore',
	'value_explain' => 'Il dato che vuoi salvare (massimo 65Kb)',
	'value_code_snipet' => 'Snippet da inserire nel tuo template:<br><code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'La tua variabile è stata creata',
	'variable_updated' => 'La tua variabile è stata aggiornata',
	'variable_deleted' => 'La tua variabile è stata eliminata',

	'field_created' => 'Il tuo campo è stato creato',
	'field_updated' => 'Il tuo campo è stato aggiornato',
	'field_deleted' => 'Il tuo campo è stato eliminato'

);
