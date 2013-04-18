<?php

return array(

	'extend' => '扩展',

	'fields' => '自定义字段',
	'fields_desc' => '添加自定义字段',

	'variables' => '变量',
	'variables_desc' => '自定义变量描述',

	'create_field' => '创建一个新的字段',
	'editing_custom_field' => '编辑字段 &ldquo;%s&rdquo;',
	'nofields_desc' => '暂无自定义字段',

	'create_variable' => '创建新变量',
	'editing_variable' => '编辑变量&ldquo;%s&rdquo;',
	'novars_desc' => '暂无自定义变量',

	// form fields
	'type' => '类型',
	'type_explain' => '字段内容的类型.',

	'field' => '字段',
	'field_explain' => 'Html 输入类型',

	'key' => '唯一键值',
	'key_explain' => '字段的唯一键值',
	'key_missing' => '请输入唯一键',
	'key_exists' => '键已经存在',

	'label' => '标签',
	'label_explain' => '字段备注',
	'label_missing' => '请输入一个标签',

	'attribute_type' => '文件类型',
	'attribute_type_explain' => '可接受的文件类型，用逗号分割，＋留空接受所有.',

	// images
	'attributes_size_width' => '图片最大宽度',
	'attributes_size_width_explain' => '如果图片宽度超过定义的图片最大宽度，图片将被裁剪',

	'attributes_size_height' => '图片最大高度',
	'attributes_size_height_explain' => '如果图片宽度超过定义的图片最大高度，图片将被裁剪',

	// custom vars
	'name' => '名称',
	'name_explain' => '唯一名称',
	'name_missing' => '请输入唯一名称',
	'name_exists' => '名称已被占用',

	'value' => '值',
	'value_explain' => '您要存储的数据（最高 64KB ）',
	'value_code_snipet' => '将被插入到模板的代码段:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => '变量已创建',
	'variable_updated' => '变量已更新',
	'variable_deleted' => '变量已删除',

	'field_created' => '字段已创建',
	'field_updated' => '字段已更新',
	'field_deleted' => '字段已删除'

);
