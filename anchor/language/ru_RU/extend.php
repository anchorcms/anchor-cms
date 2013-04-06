<?php

return array(

	'extend' => 'Разное',

	'fields' => 'Дополнительные поля',
	'fields_desc' => 'Дополнительные поля в записях и страницах',

	'variables' => 'Глобальные переменные',
	'variables_desc' => 'Создать дополнительные настройки',

	'create_field' => 'Создать новое поле',
	'editing_custom_field' => 'Редактирование &ldquo;%s&rdquo;',
	'nofields_desc' => 'Еще нет ни одного поля',

	'create_variable' => 'Создать новую переменную',
	'editing_variable' => 'Редактироввание &ldquo;%s&rdquo;',
	'novars_desc' => 'Нет переменных еще',

	// form fields
	'type' => 'Тип',
	'type_explain' => 'Тип контента, где поле будет активно.',

	'field' => 'Поле',
	'field_explain' => 'Html тип',

	'key' => 'Ключ',
	'key_explain' => 'Уникальный ключ вашего поля',
	'key_missing' => 'Пожалуйста, введите ключ',
	'key_exists' => 'Указанный ключ уже существует',

	'label' => 'Название',
	'label_explain' => 'Название для поля',
	'label_missing' => 'Пожалуйста, введите название',

	'attribute_type' => 'Типы',
	'attribute_type_explain' => 'Разделяйте запятыми.',

	// images
	'attributes_size_width' => 'Ширина',
	'attributes_size_width_explain' => 'Максимальная ширина картинки',

	'attributes_size_height' => 'Высота',
	'attributes_size_height_explain' => 'Максимальная высота картинки',

	// custom vars
	'name' => 'Имя',
	'name_explain' => 'Уникальное имя',
	'name_missing' => 'Пожалуйста введите имя',
	'name_exists' => 'Указанное имя уже занято',

	'value' => 'Содержимое',
	'value_explain' => 'Содержимое вашей переменной (до 64кб)',
	'value_code_snipet' => 'Код, добавляемый в ваш шаблон:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Ваша переменная была создана',
	'variable_updated' => 'Ваша переменная была обновлена',
	'variable_deleted' => 'Ваша переменная была удалена',

	'field_created' => 'Ваше поле было создано',
	'field_updated' => 'Ваше поле было обновлено',
	'field_deleted' => 'Ваше поле было удалено'

);