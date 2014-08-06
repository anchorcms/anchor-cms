<?php

return array(

	'extend' => 'Étendre',

	'fields' => 'Champs personnalisés',
	'fields_desc' => 'Créer un champ personnalisé',
s
	'variables' => 'Variable de site',
	'variables_desc' => 'Créé des nouvelles meta-données',

	'create_field' => 'Ajouter un nouveau champ',
	'editing_custom_field' => 'Editer le champ &ldquo;%s&rdquo;',
	'nofields_desc' => 'Aucun champ',

	'create_variable' => 'Ajouter une nouvelle variable',
	'editing_variable' => 'Éditer la variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Aucune variable',

	// form fields
	'type' => 'Type',
	'type_explain' => 'Le type de contenu que vous souhaitez lier à ce champ',

	'field' => 'Champ',
	'field_explain' => 'Type de contenu html',

	'key' => 'Clé unique',
	'key_explain' => 'La clé unique pour votre champ',
	'key_missing' => 'Veuillez entrer une clé unique',
	'key_exists' => 'Cette clé est déjà utilisée',

	'label' => 'Libellé',
	'label_explain' => 'Nom compréhensible par un humain pour votre champ',
	'label_missing' => 'Veuillez entrer un libellé',

	'attribute_type' => 'Types de fichiers',
	'attribute_type_explain' => 'Liste de fichiers séparés par des virgules, vide pour accepter tout type de fichier',

	// images
	'attributes_size_width' => 'Largeur maximale de l\'image',
	'attributes_size_width_explain' => 'Les images seront redimensionnées si elles sont plus grandes que la largeur maximale',

	'attributes_size_height' => 'Hauteur maximale de l\'image',
	'attributes_size_height_explain' => 'Les images seront redimensionnées si elles sont plus grandes que la largeur maximale',

	// custom vars
	'name' => 'Nom',
	'name_explain' => 'Un nom unique',
	'name_missing' => 'Veuillez entrer un nom unique',
	'name_exists' => 'Ce nom est déjà utilisé',

	'value' => 'Valeur',
	'value_explain' => 'La taille du fichier que vous voulez sauvegarder (jusqu\'a 64ko)',
	'value_code_snipet' => 'Code à ajouter dans votre thème :<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Votre variable a été créée',
	'variable_updated' => 'Votre variable a été mise à jour',
	'variable_deleted' => 'Votre variable a été supprimée',

	'field_created' => 'Votre champ a été créé',
	'field_updated' => 'Votre champ a été mis à jour',
	'field_deleted' => 'Votre champ a été deleted'

);
