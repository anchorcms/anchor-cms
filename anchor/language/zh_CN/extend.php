<?php

return array(

	'extend' => '扩展',

	'fields' => '自定义字段',
	'fields_desc' => '添加自定义字段',

	'variables' => '站点变量',
	'variables_desc' => '新建自定义变量',

	'create_field' => '新建字段',
	'editing_custom_field' => '编辑字段 &ldquo;%s&rdquo;',
	'nofields_desc' => '没有字段',

	'create_variable' => '新建变量',
	'editing_variable' => '编辑变量 &ldquo;%s&rdquo;',
	'novars_desc' => '没有变量',

	// form fields
	'type' => '类型',
	'type_explain' => '你添加的字段的类型.',

	'field' => '字段',
	'field_explain' => 'html input类型',

	'key' => '唯一标识',
	'key_explain' => '字段的唯一标识',
	'key_missing' => '请输入唯一标识',
	'key_exists' => 'Key已存在',

	'label' => '标签',
	'label_explain' => '人类可读的字段名称',
	'label_missing' => '请输入标签',

	'attribute_type' => '文件类型',
	'attribute_type_explain' => '支持的文件类型列表，用逗号分割。留空表示所有类型都支持.',

	// images
	'attributes_size_width' => '图片最大宽度',
	'attributes_size_width_explain' => '图片超出最大宽度时，会自动调整尺寸',

	'attributes_size_height' => '图片最大高度',
	'attributes_size_height_explain' => '图片超出最大高度时，会自动调整尺寸',

	// custom vars
	'name' => '名称',
	'name_explain' => '唯一的名称',
	'name_missing' => '请输入一个唯一的名称',
	'name_exists' => '名称已存在',

	'value' => '值',
	'value_explain' => '存储的数据(最大64kb)',
	'value_code_snipet' => '插入模版的代码片段:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => '变量已添加',
	'variable_updated' => '变量已更新',
	'variable_deleted' => '变量已删除',

	'field_created' => '字段已添加',
	'field_updated' => '字段已更新',
	'field_deleted' => '字段已删除'

);
