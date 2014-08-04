<?php

return array(

	'extend' => 'Geliþmiþ',

	'fields' => 'Özel Alanlar',
	'fields_desc' => 'Ek alanlar oluþtur',

	'variables' => 'Site Deðiþkenleri',
	'variables_desc' => 'Ek meta veriler oluþtur',

	'create_field' => 'Yeni bir alan oluþtur',
	'editing_custom_field' => 'Düzenlenen alan &ldquo;%s&rdquo;',
	'nofields_desc' => 'Henüz bir alan yok',

	'create_variable' => 'Yeni bir deðiþken oluþtur',
	'editing_variable' => 'Düzenlenen deðiþken &ldquo;%s&rdquo;',
	'novars_desc' => 'Henüz bir deðiþken yok',

	// form fields
	'type' => 'Tip',
	'type_explain' => 'Bu alana eklemek istediðiniz içerik türü.',

	'field' => 'Alan',
	'field_explain' => 'Html giriþ tipi',

	'key' => 'Benzersiz anahtar',
	'key_explain' => 'Alanýnýzýn benzersiz anahtarý',
	'key_missing' => 'Benzersiz bir anahtar girin',
	'key_exists' => 'Anahtar zaten kullanýlmýþ',

	'label' => 'Etiket',
	'label_explain' => 'Alanýnýz insanlar tarafýndan okunabilir',
	'label_missing' => 'Lütfen bir etiket girin',

	'attribute_type' => 'Dosya tipi',
	'attribute_type_explain' => 'Dosya tiplerini virgül ile ayýrýn.',

	// images
	'attributes_size_width' => 'Resim maksimum geniþliði',
	'attributes_size_width_explain' => 'Eðer resim maksimum deðerden fazla ise yeniden boyutlandýrýlýr',

	'attributes_size_height' => 'Resim maksimum yüksekliði',
	'attributes_size_height_explain' => 'Eðer resim maksimum deðerden fazla ise yeniden boyutlandýrýlýr',

	// custom vars
	'name' => 'Ýsim',
	'name_explain' => 'Benzersiz bir isim',
	'name_missing' => 'Lütfen benzersiz bir isim girim',
	'name_exists' => 'Ýsim zaten kullanýlmýþ',

	'value' => 'Deðer',
	'value_explain' => 'Saklamak istediðiniz veriler (64kb olabilir)',
	'value_code_snipet' => 'Þablona eklemek için  kod:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Deðiþkeniniz oluþturuldu',
	'variable_updated' => 'Deðiþkeniniz oluþturuldu',
	'variable_deleted' => 'Deðiþkeniniz oluþturuldu',

	'field_created' => 'Alanýnýz oluþturuldu',
	'field_updated' => 'Alanýnýz oluþturuldu',
	'field_deleted' => 'Alanýnýz oluþturuldu'

);