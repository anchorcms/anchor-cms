<?php

return array(

	'extend' => '扩展',

	'fields' => '自定义字段',
	'fields_desc' => '创建自定义字段',

	'variables' => '网站变量',
	'variables_desc' => '创建网站变量',

	'create_field' => '创建一个新的自定义字段',
	'editing_custom_field' => '编辑自定义字段 &ldquo;%s&rdquo;',
	'nofields_desc' => '还没有自定义字段',

	'create_variable' => '创建一个新的变量',
	'editing_variable' => '编辑变量 &ldquo;%s&rdquo;',
	'novars_desc' => '还没有变量',

	// form fields
	'type' => '类型',
	'type_explain' => '要添加的内容类型.',

	'field' => '形式',
	'field_explain' => 'Html 输入类型',

	'key' => '调用名',
	'key_explain' => '自定义字段的调用名',
	'key_missing' => '请输入一个调用名',
	'key_exists' => '这个名字被用了',

	'label' => '标签',
	'label_explain' => '自定义字段的备忘名',
	'label_missing' => '请输入一个标签',

	'attribute_type' => '文件类型',
	'attribute_type_explain' => '接受的文件类型,用逗号分隔,留空接受所有.',

	// images
	'attributes_size_width' => '图片最大宽度',
	'attributes_size_width_explain' => '如果图片宽度超过最大值,将会被压缩',

	'attributes_size_height' => '图片最大高度',
	'attributes_size_height_explain' => '如果图片高度超过最大值,将会被压缩',

	// custom vars
	'name' => '名称',
	'name_explain' => '唯一名称',
	'name_missing' => '请输入一个唯一名称',
	'name_exists' => '名称已被使用',

	'value' => '值',
	'value_explain' => '你要存储的数据（多达64KB）',
	'value_code_snipet' => '将被插入到模版的片段:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => '您的变量已创建',
	'variable_updated' => '您的变量已更新',
	'variable_deleted' => '您的变量已删除',

	'field_created' => '您的自定义字段已创建',
	'field_updated' => '您的自定义字段已更新',
	'field_deleted' => '您的自定义字段已删除'

);