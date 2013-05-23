<?php

return array(

	'extend' => 'Extensions',

	'fields' => 'Champs personnalisés',
	'fields_desc' => 'Créer un champ additionnel',

	'variables' => 'Variables du site',
	'variables_desc' => 'Créer des méta données additionnelles',

	'create_field' => 'Créer un nouveau champ',
	'editing_custom_field' => 'Edition du champ &ldquo;%s&rdquo;',
	'nofields_desc' => 'Pas encore de champs',

	'create_variable' => 'Créer une nouvelle variable',
	'editing_variable' => 'Edition de la variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Pas encore de variables',

	// form fields
	'type' => 'Type',
	'type_explain' => 'Le type de contenu que vous voulez ajouter à ce champ.',

	'field' => 'Champ',
	'field_explain' => 'Type d&pos;entrée html',

	'key' => 'Mot-clé',
	'key_explain' => 'Le mot-clé de votre champ',
	'key_missing' => 'Veuillez entrer un mot-clé',
	'key_exists' => 'Mot-clé déjà utilisé',

	'label' => 'Etiquette',
	'label_explain' => 'Nom humainement lisible de votre champ',
	'label_missing' => 'Veuillez entrer une étiquette',

	'attribute_type' => 'Type de fichier',
	'attribute_type_explain' => 'Liste de types de fichier acceptés(séparé par virgule), laissez vide pour tous les accepter.',

	// images
	'attributes_size_width' => 'Largeur maximale de l&rsquo;image',
	'attributes_size_width_explain' => 'Les images seront redimensionnées si elles sont plus grandes que la taille maximale',

	'attributes_size_height' => 'Hauteur maximale de l&rsquo;image',
	'attributes_size_height_explain' => 'Les images seront redimensionnées si elles sont plus grandes que la taille maximale',

	// custom vars
	'name' => 'Nom',
	'name_explain' => 'Un nom unique',
	'name_missing' => 'Veuillez entrer un nom unique',
	'name_exists' => 'Ce nom est déjà utilisé',

	'value' => 'Valeur',
	'value_explain' => 'Les données que vous voulez y stocker (jusqu&rsquo;à 64kb)',
	'value_code_snipet' => 'Fragment à insérer dans votre modèle:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Votre variable a été créée',
	'variable_updated' => 'Votre variable a été mise à jour',
	'variable_deleted' => 'Votre variable a été supprimée',

	'field_created' => 'Votre champ a été crée',
	'field_updated' => 'Votre champ a été mis à jour',
	'field_deleted' => 'Votre champ a été supprimé'

);