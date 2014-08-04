<?php

return array(

	'extend' => 'Extensão',

	'fields' => 'Campos customizados',
	'fields_desc' => 'Criar campos adicionais',

	'variables' => 'Variáveis do Site',
	'variables_desc' => 'Criar metadata adicional',

	'create_field' => 'Criar um novo campo',
	'editing_custom_field' => 'Alterando campo &ldquo;%s&rdquo;',
	'nofields_desc' => 'Ainda não há campos',

	'create_variable' => 'Criar uma nova variável',
	'editing_variable' => 'Alterando variável &ldquo;%s&rdquo;',
	'novars_desc' => 'Ainda não há variáveis',

	// form fields
	'type' => 'Tipo',
	'type_explain' => 'O tipo de conteúdo que deseja adicionar neste campo.',

	'field' => 'Campo',
	'field_explain' => 'Tipo de input (Html input type)',

	'key' => 'Chave única',
	'key_explain' => 'A chave para seu campo',
	'key_missing' => 'Por favor preencha a chave',
	'key_exists' => 'Esta chave já está em uso',

	'label' => 'Label',
	'label_explain' => 'Um nome legível para seu label',
	'label_missing' => 'Por favor preencha o label',

	'attribute_type' => 'Tipo de arquivos',
	'attribute_type_explain' => 'Separe por vírgulas a lista de extensões aceitas, deixe em branco para aceitar todas.',

	// images
	'attributes_size_width' => 'Largura máxima da imagem',
	'attributes_size_width_explain' => 'As imagens serão redimensionadas se forem maior que a largura máxima',

	'attributes_size_height' => 'Altura máxima da imagem',
	'attributes_size_height_explain' => 'As imagens serão redimensionadas se forem maior que a altura máxima',

	// custom vars
	'name' => 'Nome',
	'name_explain' => 'Um nome único',
	'name_missing' => 'Preencha o nome',
	'name_exists' => 'Este nome já está em uso',

	'value' => 'Valor',
	'value_explain' => 'O valor que deseja armazenar (até 64kb)',
	'value_code_snipet' => 'Snippet para inserir no seu template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Sua variável foi criada',
	'variable_updated' => 'Sua variável foi atualizada',
	'variable_deleted' => 'Sua variável foi excluída',

	'field_created' => 'Seu campo foi criado',
	'field_updated' => 'Seu campo foi atualizado',
	'field_deleted' => 'Seu campo foi excluído'

);