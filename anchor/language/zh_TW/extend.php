<?php

return array(

	'extend' => '擴充',

	'fields' => '自訂欄位',
	'fields_desc' => '建立額外欄位',

	'variables' => '網站變數',
	'variables_desc' => '建立額外變數',

	'create_field' => '建立新欄位',
	'editing_custom_field' => '編輯欄位 &ldquo;%s&rdquo;',
	'nofields_desc' => '還沒有欄位',

	'create_variable' => '建立新變數',
	'editing_variable' => '編輯變數 &ldquo;%s&rdquo;',
	'novars_desc' => '還沒有變數',

	// form fields
	'type' => '類型',
	'type_explain' => '你想要添加到此欄位內容的類型',

	'field' => '欄位',
	'field_explain' => 'Html input 類型',

	'key' => '唯一鍵',
	'key_explain' => '欄位的唯一鍵',
	'key_missing' => '請輸入唯一鍵',
	'key_exists' => '鍵已使用',

	'label' => '標籤',
	'label_explain' => '欄位的人類可讀名稱',
	'label_missing' => '請輸入標籤',

	'attribute_type' => '檔案類型',
	'attribute_type_explain' => '以逗號分隔接受的檔案類型，留空接受所有。',

	// images
	'attributes_size_width' => '圖片最大寬度',
	'attributes_size_width_explain' => '如果大於最大值，圖片會被調整尺寸',

	'attributes_size_height' => '圖片最大高度',
	'attributes_size_height_explain' => '如果大於最大值，圖片會被調整尺寸',

	// custom vars
	'name' => '名稱',
	'name_explain' => '唯一名稱',
	'name_missing' => '請輸入唯一名稱',
	'name_exists' => '名稱已使用',

	'value' => '值',
	'value_explain' => '你想儲存的資料（最多 64Kb）',
	'value_code_snipet' => '要插入到樣板的片段：<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => '變數已建立',
	'variable_updated' => '變數已更新',
	'variable_deleted' => '變數已刪除',

	'field_created' => '欄位已建立',
	'field_updated' => '欄位已更新',
	'field_deleted' => '欄位已刪除'

);
