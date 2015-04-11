<?php

return array(

	'extend' => 'Расширение',

	'fields' => 'Дополнительные поля',
	'fields_desc' => 'Создать дополнительные поля',

	'pagetypes' => 'Типы страниц сайта',
	'pagetypes_desc' => 'Создать другой тип страницы',

	'variables' => 'Переменые сайта',
	'variables_desc' => 'Создать дополнительное свойство',

	'create_field' => 'Создать новое поле',
	'editing_custom_field' => 'Редактировать поле &ldquo;%s&rdquo;',
	'nofields_desc' => 'Нет полей',

	'create_variable' => 'Создать новую переменую',
	'editing_variable' => 'Редактировать переменую &ldquo;%s&rdquo;',
	'novars_desc' => 'Нет переменых',

	'create_pagetype' => 'Создать новый тип страницы',
	'editing_pagetype' => 'Редактировать тип страницы &ldquo;%s&rdquo;',

	// form fields
	'type' => 'Тип',
	'type_explain' => 'Тип содержимого, которое вы хотите добавить.',
	'notypes_desc' => 'Нет типов',

	'pagetype' => 'Тип страницы',
	'pagetype_explain' => 'Тип страницы, который вы хотите добавить.',

	'field' => 'Поле',
	'field_explain' => 'тип Html input',

	'key' => 'Уникальный ключ',
	'key_explain' => 'Уникальный ключ вашего поля',
	'key_missing' => 'Пожалуйста, введите уникальный ключ',
	'key_exists' => 'Такой ключ уже используется',

	'label' => 'Имя поля',
	'label_explain' => 'Человекочитаемое имя для вашего поля',
	'label_missing' => 'Пожалуйста, введите имя',

	'attribute_type' => 'Типы файлов',
	'attribute_type_explain' => 'Список разрешенных типов файлов, разделенных запятой. Пустая строка для разрешения всех типов.',

	// images
	'attributes_size_width' => 'Максимальная длина изображения',
	'attributes_size_width_explain' => 'Изображение будет уменьшено, если его длина превышает максимальную',

	'attributes_size_height' => 'Максимальная высота изображения',
	'attributes_size_height_explain' => 'Изображение будет уменьшено, если его высота превышает максимальную',

	// custom vars
	'name' => 'Name',
	'name_explain' => 'Уникальное имя',
	'name_missing' => 'Пожалуйста, введите уникальное имя',
	'name_exists' => 'Такое имя уже используется',

	'value' => 'Значение',
	'value_explain' => 'Данные, которые вы хотите хранить (до 64KB)',
	'value_code_snipet' => 'Сниппет для вставки в шаблон:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Ваша переменная была создана',
	'variable_updated' => 'Ваша переменная была обновлена',
	'variable_deleted' => 'Ваша переменная была удалена',

	'pagetype_created' => 'Ваш тип страницы был создан',
	'pagetype_updated' => 'Ваш тип страницы был обновлен',
	'pagetype_deleted' => 'Ваш тип страницы был удален',

	'field_created' => 'Ваше поле было создано',
	'field_updated' => 'Ваше поле было обновлено',
	'field_deleted' => 'Ваше поле было удалено'

);
