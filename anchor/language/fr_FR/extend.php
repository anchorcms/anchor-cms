<?php

return array(

	'extend' => 'Extensions',

	'fields' => 'Champs additionnels',
	'fields_desc' => 'Créer des champs additionnels',

	'variables' => 'Variables du site',
	'variables_desc' => 'Créer une variable supplémentaire',

	'create_field' => 'Créer un nouveau champ',
	'editing_custom_field' => 'Modifier le champ &ldquo;%s&rdquo;',
	'nofields_desc' => 'Pas encore de champs',

	'create_variable' => 'Créer une nouvelle variable',
	'editing_variable' => 'Modifier le variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Pas encore de variables',

	// form fields
	'type' => 'Type',
	'type_explain' => 'Le type du contenu de ce champ.',

	'field' => 'Champ',
	'field_explain' => 'Contenu de type HTML',

	'key' => 'Clé unique',
	'key_explain' => 'La clé unique de votre champ',
	'key_missing' => 'Veuillez renseigner une clé unique',
	'key_exists' => 'Cette clé est déjà utilisée',

	'label' => 'Nom',
	'label_explain' => 'Le nom de votre catégorie',
	'label_missing' => 'Veuillez renseigner un nom',

	'attribute_type' => 'Types de fichiers',
	'attribute_type_explain' => 'Une liste séparée par des virgules de tous les types de fichiers que vous souhaitez accepter. Laissez ce champ vide pour accepter tous les types.',

	// images
	'attributes_size_width' => 'Largeur maximale de l\'image',
	'attributes_size_width_explain' => 'Les images seront réduites si elles sont plus larges que la largeur maximale',

	'attributes_size_height' => 'Hauteur maximale de l\'image',
	'attributes_size_height_explain' => 'Les images seront réduites si elles sont plus hautes que la largeur maximale',

	// custom vars
	'name' => 'Nom',
	'name_explain' => 'Le nom unique',
	'name_missing' => 'Veuillez renseigner un nom unique',
	'name_exists' => 'Ce nom est déjà utilisé',

	'value' => 'Valeur',
	'value_explain' => 'Les données que vous souhaitez socker (64kb maximum)',
	'value_code_snipet' => 'Le snippet à insérer dans votre modèle:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Votre variable a été créée',
	'variable_updated' => 'Votre variable a été mise à jour',
	'variable_deleted' => 'Votre variable a été supprimée',

	'field_created' => 'Votre champ a été crée',
	'field_updated' => 'Votre champ a été mis à jour',
	'field_deleted' => 'Votre champ a été supprimé'

);
