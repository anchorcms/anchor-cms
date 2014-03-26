<?php

return array(

	'extend' => 'Extensões',

	'fields' => 'Campos Customizados',
	'fields_desc' => 'Criar campos adicionais',

	'variables' => 'Variáveis para o site',
	'variables_desc' => 'Criar dados adicionais',

	'create_field' => 'Criar um novo campo',
	'editing_custom_field' => 'Editando o campo &ldquo;%s&rdquo;',
	'nofields_desc' => 'Nehum campo',

	'create_variable' => 'Criar nova variável',
	'editing_variable' => 'Editando a variável &ldquo;%s&rdquo;',
	'novars_desc' => 'Nenhuma variável',

	// form fields
	'type' => 'Tipo',
	'type_explain' => 'O tipo de conteúdo que você quer que este campo receba.',

	'field' => 'Campo',
	'field_explain' => 'Tipo de entrada HTML',

	'key' => 'Identificador único',
	'key_explain' => 'O identificador único para este campo',
	'key_missing' => 'Por favor adicione um identificador único',
	'key_exists' => 'Indentificador não é único',

	'label' => 'Título',
	'label_explain' => 'Título legível para o seu campo',
	'label_missing' => 'Por favor adicione um título',

	'attribute_type' => 'Tipos de arquivo',
	'attribute_type_explain' => 'Separe por vírgulas os tipos de arquivo aceitos. Deixe em branco para aceitar qualquer tipo.',

	// images
	'attributes_size_width' => 'Largura máxima da imagem',
	'attributes_size_width_explain' => 'Imagens serão redimensionadas forem maiores que este tamanho',

	'attributes_size_height' => 'Altura máxima da imagem',
	'attributes_size_height_explain' => 'Imagens serão redimensionadas forem maiores que este tamanho',

	// custom vars
	'name' => 'Nome',
	'name_explain' => 'Um nome único',
	'name_missing' => 'Por favor informe um nome',
	'name_exists' => 'O nome já está em uso',

	'value' => 'Valor',
	'value_explain' => 'Os dados que você quer inserir (até 64kb)',
	'value_code_snipet' => 'Snippet para inserir no seu template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'A variável foi criada',
	'variable_updated' => 'A variável foi atualizada',
	'variable_deleted' => 'A variável foi removida',

	'field_created' => 'O campo foi criado',
	'field_updated' => 'O campo foi atualizado',
	'field_deleted' => 'O campo foi removido'

);
