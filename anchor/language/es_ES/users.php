<?php

return array(

	'users' => 'Usuarios',

	'create_user' => 'Crear un nuevo usuario',
	'add_user' => 'Añadir un nuevo usuario',
	'editing_user' => 'Editando el perfil de %s&rsquo;',
	'remembered' => 'Se mi contraseña',
	'forgotten_password' => 'Has olvidado tu contraseña?',

	// roles
	'administrator' => 'Admin',
	'administrator_explain' => '',

	'editor' => 'Editor',
	'editor_explain' => '',

	'user' => 'Usuario',
	'user_explain' => '',

	// form fields
	'real_name' => 'Nombre Real',
	'real_name_explain' => '',

	'bio' => 'Biografía',
	'bio_explain' => '',

	'status' => 'Estado',
	'status_explain' => '',

	'role' => 'Rol',
	'role_explain' => '',

	'username' => 'Nombre de usuario',
	'username_explain' => '',
	'username_missing' => 'Por favor introduce un nombre de usuario, debe contener al menos %s letras',

	'password' => 'Contraseña',
	'password_explain' => '',
	'password_too_short' => 'La contraseña debe contener al menos %s caracteres',

	'new_password' => 'Nueva contraseña',

	'email' => 'Correo Electrónico',
	'email_explain' => '',
	'email_missing' => 'Introduce una dirección de correo electrónico válida',
	'email_not_found' => 'Perfil no encontrado.',

	// messages
	'updated' => 'Perfil de usuario actualizado.',
	'created' => 'Perfil de usuario creado.',
	'deleted' => 'Perfil de usuario eliminado.',
	'delete_error' => 'No puedes eliminar tu propio perfil',
	'login_error' => 'Nombre de usuario o contraseña no es correcto.',
	'logout_notice' => 'Ahora estás desconectado.',
	'recovery_sent' => 'Te hemos enviado un correo electrónico para confirmar el cambio de contraseña.',
	'recovery_expired' => 'El token de recuperación de contraseña ha caducado, por favor, vuelve a intentarlo.',
	'password_reset' => 'Tu contraseña ha sido establecida. Ya puedes iniciar sesión!',

	// password recovery email
	'recovery_subject' => 'Resetear contraseña',
	'recovery_message' => 'Has solicitado un reseteo de la contraseña.' .
		'Para continuar sigue el enlace siguiente.' . PHP_EOL . '%s',

);