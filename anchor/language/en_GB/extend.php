<?php

return array(

	'extend' => 'Extend',

	'fields' => 'Custom Fields',
	'fields_desc' => 'Create additional fields',

	'pagetypes' => 'Site Page Types',
	'pagetypes_desc' => 'Create different page types',

	'variables' => 'Site Variables',
	'variables_desc' => 'Create additional metadata',

	'create_field' => 'Create a new field',
	'editing_custom_field' => 'Editing field &ldquo;%s&rdquo;',
	'nofields_desc' => 'No fields yet',

	'create_variable' => 'Create a new variable',
	'editing_variable' => 'Editing variable &ldquo;%s&rdquo;',
	'novars_desc' => 'No variables yet',

	'create_pagetype' => 'Create a new page type',
	'editing_pagetype' => 'Editing page type &ldquo;%s&rdquo;',

	// form fields
	'type' => 'Type',
	'type_explain' => 'The type of content you want to add this field to.',
	'notypes_desc' => 'No page types yet',

	'pagetype' => 'Page Type',
	'pagetype_explain' => 'The type of page you want to add this field to.',

	'field' => 'Field',
	'field_explain' => 'Html input type',

	'key' => 'Unique Key',
	'key_explain' => 'The unique key for your field',
	'key_missing' => 'Please enter a unique key',
	'key_exists' => 'Key is already in use',

	'label' => 'Label',
	'label_explain' => 'Human readable name for your field',
	'label_missing' => 'Please enter a label',

	'attribute_type' => 'File types',
	'attribute_type_explain' => 'Comma separated list of accepted file types, empty to accept all.',

	// images
	'attributes_size_width' => 'Image max width',
	'attributes_size_width_explain' => 'Images will be resized if they are bigger than the max size',

	'attributes_size_height' => 'Image max height',
	'attributes_size_height_explain' => 'Images will be resized if they are bigger than the max size',

	// custom vars
	'name' => 'Name',
	'name_explain' => 'A unique name',
	'name_missing' => 'Please enter a unique name',
	'name_exists' => 'Name is already in use',

	'value' => 'Value',
	'value_explain' => 'The data you want to store (up to 64kb)',
	'value_code_snipet' => 'Snippet to insert into your template:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Your variable was created',
	'variable_updated' => 'Your variable was updated',
	'variable_deleted' => 'Your variable was deleted',

	'pagetype_created' => 'Your page type was created',
	'pagetype_updated' => 'Your page type was updated',
	'pagetype_deleted' => 'Your page type was deleted',

	'field_created' => 'Your field was created',
	'field_updated' => 'Your field was updated',
	'field_deleted' => 'Your field was deleted'

);
