<?php defined('IN_CMS') or die('No direct access allowed.');

return array(

	'pages' => 'Pagina&rsquo;ss',
	'create_page' => 'Maak een nieuwe pagina',
	'no_pages' => 'Nog geen pagina&rsquo;ss.',
	'add_page' => 'Voeg een pagina toe.',
	'editing' => 'Bewerken',
	'editing_explain' => 'Wat handige links.',
	'view_page' => 'Laat deze pagina op je website zien.',

	'name' => 'Naam',
	'name_explain' => 'The naam van je pagina. Dit is te zien in de navigatie.',
	'title' => 'Titel',
	'title_explain' => 'The ttel van je pagina, die is te zien in de <code>&lt;title&gt;</code>.',
	'slug' => 'Slug',
	'slug_explain' => 'De slug voor je bericht (<code>' . $_SERVER['HTTP_HOST'] . '/<span id="output">slug</span></code>).',
	'content' => 'Inhoud',
	'content_explain' => 'De inhoud van je pagina. Accepteert geldige HTML',
	'status' => 'Status',
	'status_explain' => 'Wil je je pagina live (gepubliceerd), wachtend (concept), of verborgen (gearchiveerd)?',

	'draft' => 'Concept',
	'archived' => 'Gearchiveerd',
	'published' => 'Gepubliceerd',

	'create' => 'Maak',
	'delete' => 'Verwijder',
	'save' => 'Sla op',
	'return_pages' => 'Terug naar pagina&rsquo;ss',

	'missing_name' => 'Vul een naam in',
	'missing_title' => 'Vul een titel in',
	'duplicate_slug' => 'Een pagina met deze slug bestaat al, verander je slug',
	'page_success_created' => 'Je nieuwe pagina is toegevoegd',
	'page_success_updated' => 'Je pagina is bijgewerkt',
	'page_success_delete' => 'Je pagina is verwijderd',
	'page_error_delete' => 'Sorry, je kan je homepage of berichtenpagina niet verwijderen.'

);