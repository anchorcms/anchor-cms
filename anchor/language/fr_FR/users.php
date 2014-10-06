<?php

return array(

	'users' => 'Utilisateurs',

	'create_user' => 'Créer un nouvel utilisateur',
	'add_user' => 'Ajouter un utilisateur',
	'editing_user' => 'Modifier le profil de %s',
	'remembered' => 'Je connais mon mot de passe',
	'forgotten_password' => 'Vous avez oublié votre mot de passe?',

	// roles
	'administrator' => 'Administrateur',
	'administrator_explain' => '',

	'editor' => 'Éditeur',
	'editor_explain' => '',

	'user' => 'Utilisateur',
	'user_explain' => '',

	// form fields
	'real_name' => 'Prénom & nom',
	'real_name_explain' => '',

	'bio' => 'Biographie',
	'bio_explain' => '',

	'status' => 'Status',
	'status_explain' => '',

	'role' => 'Role',
	'role_explain' => '',

	'username' => 'Identifiant',
	'username_explain' => '',
	'username_missing' => 'Veuillez renseigner un nom d\'utilisateur, d\'au moins %s caractères',

	'password' => 'Mot de passe',
	'password_explain' => '',
	'password_too_short' => 'Le mot de passe doit-être d\'au moins %s caractères',

	'new_password' => 'Nouveau mot de passe',

	'email' => 'Email',
	'email_explain' => '',
	'email_missing' => 'Merci de renseigner un email valide',
	'email_not_found' => 'Profil introuvable.',

	// messages
	'updated' => 'Profil utilisateur mis à jour.',
	'created' => 'Profil utilisateur crée.',
	'deleted' => 'Profil utilisateur supprimé.',
	'delete_error' => 'Vous ne pouvez supprimer votre propre profil',
	'login_error' => 'Le nom d\'utilisateur ou le mot de passe ne sont pas bons.',
	'logout_notice' => 'Vous êtes maintenant déconnecté.',
	'recovery_sent' => 'Nous vous avons envoyé un email pour la confirmation de votre changement de mot de passe.',
	'recovery_expired' => 'Le token de récupération de mot de passe a expiré, merci de réessayer.',
	'password_reset' => 'Votre mot de passe a été mis à jour. Vous pouvez vous connecter maintenant!',

	// password recovery email
	'recovery_subject' => 'Réinitialisation du mot de passe',
	'recovery_message' => 'Vous avez demandé à réinitialiser votre mot de passe.' .
		'Pour continuer, suivez le lien ci-dessous.' . PHP_EOL . '%s',

);