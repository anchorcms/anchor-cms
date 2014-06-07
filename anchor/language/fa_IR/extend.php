<?php

return array(

	'extend' => 'توسعه',

	'fields' => 'فیلدهای سفارشی',
	'fields_desc' => 'ایجاد فیلدهای اضافی',

	'variables' => 'متغیرهای سایت',
	'variables_desc' => 'ایجاد فرادادۀ اضافی',

	'create_field' => 'ایجاد فیلد جدید',
	'editing_custom_field' => 'در حال ویرایش فیلد &ldquo;%s&rdquo;',
	'nofields_desc' => 'هنوز هیچ فیلدی وجود ندارد',

	'create_variable' => 'ایجاد متغیر جدید',
	'editing_variable' => 'در حال ویرایش متغیر &ldquo;%s&rdquo;',
	'novars_desc' => 'هنوز هیچ متغیری وجود ندارد',

	// form fields
	'type' => 'نوع',
	'type_explain' => 'نوع محتوایی که می‌خواهید این فیلد را به آن بیافزایید',

	'field' => 'فیلد',
	'field_explain' => 'Html input type',

	'key' => 'کلید یکتا',
	'key_explain' => 'کلید یکتایی برای  فیلدتان',
	'key_missing' => 'لطفاً یک کلید یکتا وارد کنید',
	'key_exists' => 'این کلید از قبل موجود است',

	'label' => 'برچسب',
	'label_explain' => 'نام خوانا توسط انسان برای فیلدتان',
	'label_missing' => 'لطفاً یک برچسب وارد کنید',

	'attribute_type' => 'انواع فایل',
	'attribute_type_explain' => 'فهرست انواع فایل‌های قابل قبول که بوسیلۀ کاما جدا شده است، خالی بگذارید تا همه پذیرفته شوند.',

	// images
	'attributes_size_width' => 'حداکثر عرض تصویر',
	'attributes_size_width_explain' => 'انداژۀ تصاویر تغییر خواهد کرد اگر بیشتر از حداکثر اندازه باشد',

	'attributes_size_height' => 'حداکثر ارتفاع صویر',
	'attributes_size_height_explain' => 'انداژۀ تصاویر تغییر خواهد کرد اگر بیشتر از حداکثر اندازه باشد',

	// custom vars
	'name' => 'نام',
	'name_explain' => 'یک نام یکتا',
	'name_missing' => 'لطفاً یک نام یکتا وارد کنید',
	'name_exists' => 'این نام قبلاً استفاده شده است',

	'value' => 'مقدار',
	'value_explain' => 'داده‌ای که می‌خواهید ذخیر کنید (حداکثر 64kb)‌',
	'value_code_snipet' => 'کدی که به قالب خود می‌افزایید:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'متغیر شما ایجاد شد',
	'variable_updated' => 'متغیر شما به‌روز شد',
	'variable_deleted' => 'متغیر شما حذف شد',

	'field_created' => 'فیلد شما ایجاد شد',
	'field_updated' => 'فیلد شما به‌روز شد',
	'field_deleted' => 'فیلد شما حذف شد'

);
