<?php defined('IN_CMS') or die('No direct access allowed.');

return array(

	'pages' => 'Pagina\'s',
	'create_page' => 'Maak een nieuwe pagina',
	'no_pages' => 'Er zijn geen pagina\'s, wil je er een maken?',
	'add_page' => 'Pagina toevoegen',
	'editing' => 'Wijzigen',
	'editing_explain' => 'Een aantal handige links.',
	'view_page' => 'Bekijk deze pagina op je website',

	'name' => 'Naam',
	'name_explain' => 'De naam van je pagina welke je te zien krijgt in het menu',
	'title' => 'Titel',
	'title_explain' => 'De titel van je pagina, deze komt te staan in de <code>&lt;title&gt;</code>.',
	'slug' => 'Url',
	'slug_explain' => 'De url van je pagina (<code>' . $_SERVER['HTTP_HOST'] . '/<span id="output">url</span></code>).',
	'content' => 'Inhoud',
	'content_explain' => 'De inhoud van je pagina. Accepteert valide HTML.',
	'redirect_option' => 'Deze pagina verwijst door naar een andere url',
	'redirect_url' => 'Verwijzende url',
	'status' => 'Status',
	'status_explain' => 'Wil je deze pagina live (gepubliceerd), in de wacht (ontwerp), of ontzichtbaar (gearchiveerd)?',

	'draft' => 'Ontwerp',
	'archived' => 'Gearchiveerd',
	'published' => 'Gepubliceerd',

	'create' => 'Aanmaken',
	'delete' => 'Verwijderen',
	'save' => 'Opslaan',
	'return_pages' => 'Ga terug naar pagina\'s',

	'delete_confirm' => 'Weet je zeker dat je deze pagina wilt verwijderen?', 
	'delete_confirm_submit' => 'Ja, ik wil dat deze pagina permanent wordt verwijderd', 
	'delete_confirm_cancel' => 'Nee, behoud deze pagina',

	'missing_name' => 'Vul een naam in',
	'missing_title' => 'Vul een titel in',
	'duplicate_slug' => 'Er bestaat al een pagina met deze url, verander deze alsjeblieft',
	'page_success_created' => 'Jouw nieuwe pagina is toegevoegd',
	'page_success_updated' => 'De pagina is succesvol veranderd',
	'page_success_delete' => 'De pagina is succesvol verwijderd',
	'page_error_delete' => 'Sorry, je kunt je homepage en berichten pagina niet verwijderen.'

);
