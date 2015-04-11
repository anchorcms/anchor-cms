<?php

return array(

	'users' => 'Пользователи',

	'create_user' => 'Создать нового пользователя',
	'add_user' => 'Добавить нового пользователя',
	'editing_user' => 'Редактировать профиль %s',
	'remembered' => 'Я знаю свой пароль',
	'forgotten_password' => 'Забыли ваш пароль?',

	// roles
	'administrator' => 'Администратор',
	'administrator_explain' => '',

	'editor' => 'Редактор',
	'editor_explain' => '',

	'user' => 'Пользователь',
	'user_explain' => '',

	// form fields
	'real_name' => 'Настоящее имя',
	'real_name_explain' => '',

	'bio' => 'Биография',
	'bio_explain' => '',

	'status' => 'Статус',
	'status_explain' => '',

	'role' => 'Роль',
	'role_explain' => '',

	'username' => 'Имя пользователя',
	'username_explain' => '',
	'username_missing' => 'Пожалуйста, введите имя пользователя, включающее не менее %s символов',

	'password' => 'Пароль',
	'password_explain' => '',
	'password_too_short' => 'Пароль должен быть не менее %s символов',

	'new_password' => 'Новый пароль',

	'email' => 'Email',
	'email_explain' => '',
	'email_missing' => 'Пожалуйста, введите валидный email',
	'email_not_found' => 'Профиль не найден.',

	// messages
	'updated' => 'Профиль пользователя был обновлен.',
	'created' => 'Профиль пользователя был создан.',
	'deleted' => 'Профиль пользователя был удален.',
	'delete_error' => 'Вы не можете удалить свой собственный профиль',
	'login_error' => 'Имя пользователя или пароль некорректны.',
	'logout_notice' => 'Вы уже вышли.',
	'recovery_sent' => 'Мы выслали вам email для подтверждения смены пароля.',
	'recovery_expired' => 'Срок действия токена восстановления пароля истек, пожалуйста, попробуйте заново.',
	'password_reset' => 'Ваш новый пароль был установлен. Идите и авторизуйтесь!',

	// password recovery email
	'recovery_subject' => 'Сброс пароля',
	'recovery_message' => 'Вы запросили сброс пароля. ' .
		'Для проболжения следуйте по ссылке.' . PHP_EOL . '%s',

);