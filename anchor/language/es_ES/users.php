<?php

return array(

  'users' => 'Usuarios',

  'create_user' => 'Crear nuevo usuario',
  'add_user' => 'Añadir nuevo usuario',
  'editing_user' => 'Editando el perfil de %s&rsquo;s',
  'remembered' => 'Conozco mi contraseña',
  'forgotten_password' => '¿Has olvidado la contraseña?',

  // roles
  'administrator' => 'Administrador',
  'administrator_explain' => '',

  'editor' => 'Editor',
  'editor_explain' => '',

  'user' => 'Usuario',
  'user_explain' => '',

  // form fields
  'real_name' => 'Nombre real',
  'real_name_explain' => '',

  'bio' => 'Biografía',
  'bio_explain' => '',

  'status' => 'Estado',
  'status_explain' => '',

  'role' => 'Rol',
  'role_explain' => '',

  'username' => 'Nombre de usuario',
  'username_explain' => '',
  'username_missing' => 'Por favor, introduce un nombre de usuario. Debe tener al menos %s caracteres',

  'password' => 'Contraseña',
  'password_explain' => '',
  'password_too_short' => 'Por favor, introduce una contraseña. Debe tener al menos %s caracteres',

  'new_password' => 'Nueva contraseña',

  'email' => 'Email',
  'email_explain' => '',
  'email_missing' => 'Por favor, introduce una dirección de email válida',
  'email_not_found' => 'Perfil no encontrado.',

  // messages
  'updated' => 'Perfil de usuario actualizado.',
  'created' => 'Perfil de usuario creado.',
  'deleted' => 'Perfil de usuario eliminado.',
  'delete_error' => 'No puedes borrar tu propio perfil',
  'login_error' => 'Nombre de usuario o contraseña incorrectos.',
  'logout_notice' => 'Has cerrado sesión.',
  'recovery_sent' => 'Te hemos enviado un email para confirmar tu cambio de contraseña.',
  'recovery_expired' => 'El token de recuperación de contraseña ha expirado, por favor, intentalo de nuevo.',
  'password_reset' => '¡Se ha establecido una nueva contraseña!. ¡Inicia sesión ahora!',

  // password recovery email
  'recovery_subject' => 'Resetear contraseña',
  'recovery_message' => 'Has solicitado el reseteo de tu contraseña' .
    'Para continuar, haz click en el siguiente link.' . PHP_EOL . '%s',

);