<?php

return array(

	'extend' => 'Extender',

	'fields' => 'Campos personalizados',
	'fields_desc' => 'Crea campos adicionales',

	'variables' => 'Variables del sitio',
	'variables_desc' => 'Crea metadatos adicionales',

	'create_field' => 'Crea un nuevo campo',
	'editing_custom_field' => 'Editando campo &ldquo;%s&rdquo;',
	'nofields_desc' => 'No hay campos todavía',

	'create_variable' => 'Crea una nueva variable',
	'editing_variable' => 'Editando variable &ldquo;%s&rdquo;',
	'novars_desc' => 'No hay variables todavía',

	// form fields
	'type' => 'Tipo',
	'type_explain' => 'El tipo de contenido del campo que vas a crear.',


	'field' => 'Campo',
	'field_explain' => 'Tipo de input Html',

	'key' => 'Clave única',
	'key_explain' => 'La clave única para tu campo',
	'key_missing' => 'Por favor, introduce una clave única',
	'key_exists' => 'La clave única está en uso',

	'label' => 'Etiqueta',
	'label_explain' => 'Nombre para tu campo',
	'label_missing' => 'Por favor, introduce una etiqueta',

	'attribute_type' => 'Tipos de ficheros',
	'attribute_type_explain' => 'Lista de extensiones aceptadas, separadas por comas. En blanco para aceptar todas.',

	// images
	'attributes_size_width' => 'Ancho máximo de la imagen',
	'attributes_size_width_explain' => 'Las imágenes serán redimensionadas si superan el tamaño máximo de la imagen',

	'attributes_size_height' => 'Alto máximo de la imagen',
	'attributes_size_height_explain' => 'Las imágenes serán redimensionadas si superan el tamaño máximo de la imagen',

	// custom vars
	'name' => 'Nombre',
	'name_explain' => 'Un nombre único',
	'name_missing' => 'Por favor, introduce un nombre único',
	'name_exists' => 'El nombre está en uso',

	'value' => 'Valor',
	'value_explain' => 'Los datos que quieres almacenar (hasta 64kb)',
	'value_code_snipet' => 'Snippet a insertar en tu plantilla:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Tu variable ha sido creada',
	'variable_updated' => 'Tu variable ha sido actualizada',
	'variable_deleted' => 'Tu variable ha sido eliminada',

	'field_created' => 'Tu campo ha sido creado',
	'field_updated' => 'Tu campo ha sido actualizado',
	'field_deleted' => 'Tu campo ha sido eliminado'

);
