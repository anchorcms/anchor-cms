<?php

return array(

	'posts' => 'Article',

	'create_post' => 'Créer un nouvel article',
	'noposts_desc' => 'Vous avez aucun articles !',

	// form fields
	'title' => 'Titre de l\'article',
	'title_explain' => '',
	'title_missing' => 'Le titre de l\'article doit faire plus de 3 charactères',

	'content' => 'Contenu de l\'article',
	'content_explain' => 'Écrivez ce qui vous passe par la tête.',

	'slug' => 'Slug',
	'slug_explain' => 'Slug uri to identify your post, should only contain ascii characters',
	'slug_missing' => 'The slug must be atleast 3 characters, slugs can only contain ascii characters',
	'slug_duplicate' => 'Slug already exists',
	'slug_invalid' => 'Slug must contain letters',

	'time' => 'Publié le (GMT)',
	'time_explain' => 'Format : YYYY-MM-DD HH:MM:SS',
	'time_invalid' => 'Mauvais format de date',

	'description' => 'Description',
	'description_explain' => '',

	'status' => 'Statut',
	'status_explain' => '',

	'category' => 'Categorie',
	'category_explain' => '',

	'allow_comments' => 'Autoriser les commentaires',
	'allow_comments_explain' => '',

	'custom_css' => 'CSS personnalisé',
	'custom_css_explain' => '',

	'custom_js' => 'JS personnalisé',
	'custom_js_explain' => '',

	// messages
	'updated' => 'Votre article a bien été mis à jour',
	'created' => 'Votre article a bien été créé',
	'deleted' => 'Votre article a bien été supprimé'

);
