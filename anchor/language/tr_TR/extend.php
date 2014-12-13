<?php

return array(

	'extend' => 'Gelişmiş',

	'fields' => 'Özel Alanlar',
	'fields_desc' => 'Yeni özel alanlar oluştur',

	'variables' => 'Site Değişkenleri',
	'variables_desc' => 'Yeni değişkenler oluştur',

	'create_field' => 'Yeni alan oluştur',
	'editing_custom_field' => '&ldquo;%s&rdquo; alanını düzenle',
	'nofields_desc' => 'Hiç özel alan yok',

	'create_variable' => 'Yeni değişken oluştur',
	'editing_variable' => '&ldquo;%s&rdquo; değişkenini düzenle',
	'novars_desc' => 'Hiç değişken yok',

	// form fields
	'type' => 'Tip',
	'type_explain' => 'Bu alanı eklemek istediğin içerik tipini seç.',

	'field' => 'Alan',
	'field_explain' => 'Html girdi tipi',

	'key' => 'Benzersiz Anahtar',
	'key_explain' => 'Alanın eşsiz anahtarı.',
	'key_missing' => 'Lütfen benzersiz bir anahtar gir',
	'key_exists' => 'Anahtar zaten kullanımda',

	'label' => 'Etiket',
	'label_explain' => 'Alanın okunabilir etiketi',
	'label_missing' => 'Lütfen bir etiket gir',

	'attribute_type' => 'Dosya Tipleri',
	'attribute_type_explain' => 'Kabul edilir dosya tiplerini virgül ile ayırarak yaz. Tüm dosyaları kabul etmek için boş bırakmalısın.',

	// images
	'attributes_size_width' => 'Imaj Maks. Uzunluk',
	'attributes_size_width_explain' => 'Eğer imaj uzunluğu girilen değerden büyükse bu boyuta küçültülür.',

	'attributes_size_height' => 'Imaj Maks. Yükseklik',
	'attributes_size_height_explain' => 'Eğer imaj yüksekliği girilen değerden büyükse bu boyuta küçültülür.',

	// custom vars
	'name' => 'İsim',
	'name_explain' => 'Benzersiz bir isim',
	'name_missing' => 'Lütfen benzersiz bir isim gir',
	'name_exists' => 'İsim zaten kullanımda',

	'value' => 'Değer',
	'value_explain' => 'Değikenin tutmasını istediğin değer (en fazla 64kb)',
	'value_code_snipet' => 'Temanda kullanmak için snippet kodu:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Değişken oluşturuldu',
	'variable_updated' => 'Değişken düzenlendi',
	'variable_deleted' => 'Değişken silindi',

	'field_created' => 'Özel alan oluşturuldu',
	'field_updated' => 'Özel alan düzenlendi',
	'field_deleted' => 'Özel alan silindi'

);
