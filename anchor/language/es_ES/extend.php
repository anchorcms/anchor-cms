<?php

return array(

	'extend' => 'Expandir',

	'fields' => 'Campos Personalizados',
	'fields_desc' => 'Crear Campos adicionales',

	'variables' => 'Variables del sitio',
	'variables_desc' => 'Crear metadatos adicionales',

	'create_field' => 'Crear nuevo campo.',
	'editing_custom_field' => 'Editando el campo &ldquo;%s&rdquo;',
	'nofields_desc' => 'No hay campos aún',

	'create_variable' => 'Crear una nueva variable.',
	'editing_variable' => 'Editando la variable &ldquo;%s&rdquo;',
	'novars_desc' => 'No hay variables todavía',

	// form fields
	'type' => 'Tipo',
	'type_explain' => 'El tipo de contenido al que quieres añadir este campo.',

	'field' => 'Campo',
	'field_explain' => 'Tipo de campo HTML',

	'key' => 'Clave única',
	'key_explain' => 'Clave única para tu campo',
	'key_missing' => 'Por Favor introduce una clave única',
	'key_exists' => 'Esta clave está en uso',

	'label' => 'Etiqueta',
	'label_explain' => 'Nombre legible por humanos para tu campo',
	'label_missing' => 'Por Favor introduce la etiqueta',

	'attribute_type' => 'Tipos de ficheros',
	'attribute_type_explain' => 'Listado separado por comas de los tipos de fichero aceptados, en blanco acepta todos.',

	// images
	'attributes_size_width' => 'Ancho máximo de la imágen',
	'attributes_size_width_explain' => 'Las imágenes serán redimensionadas si son mas grandes de este ancho',

	'attributes_size_height' => 'Álto máximo de la imágen',
	'attributes_size_height_explain' => 'Las imágenes serán redimensionadas si son mas grandes de este alto',

	// custom vars
	'name' => 'Nombre',
	'name_explain' => 'Un nombre único',
	'name_missing' => 'Por favor introduce un nombre único',
	'name_exists' => 'Este nombre ya está en uso',

	'value' => 'Valor',
	'value_explain' => 'Datos a almacenar (hasta 64kb)',
	'value_code_snipet' => 'Fragmento a insertar en la plantilla:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Tu variable ha sido creada',
	'variable_updated' => 'Tu variable ha sido actualizada',
	'variable_deleted' => 'Tu variable ha sido eliminada',

	'field_created' => 'Tu campo ha sido creado',
	'field_updated' => 'Tu campo ha sido actualizado',
	'field_deleted' => 'Tu campo ha sido eliminado'

);