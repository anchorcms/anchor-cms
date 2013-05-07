<?php

return array(

	'extend' => 'Kembangkan',

	'fields' => 'Custom Fields',
	'fields_desc' => 'Buat field baru',

	'variables' => 'Site Variables',
	'variables_desc' => 'Buat metadata tambahan',

	'create_field' => 'Buat field baru',
	'editing_custom_field' => 'Mengubah field &ldquo;%s&rdquo;',
	'nofields_desc' => 'Belum ada field tambahan',

	'create_variable' => 'Buat variable baru',
	'editing_variable' => 'Mengubah variable &ldquo;%s&rdquo;',
	'novars_desc' => 'Belum ada variable',

	// form fields
	'type' => 'Jenis',
	'type_explain' => 'Jenis konten yang akan diasosiasikan dengan field ini.',

	'field' => 'Field',
	'field_explain' => 'Jenis masukan dalam HTML',

	'key' => 'Kunci Unik',
	'key_explain' => 'Kata kunci unik untuk menandai field ini',
	'key_missing' => 'Harap masukkan kata kunci',
	'key_exists' => 'Kata kunci sudah digunakan',

	'label' => 'Label',
	'label_explain' => 'Label yang mudah dipahami untuk field anda',
	'label_missing' => 'Harap masukkan label',

	'attribute_type' => 'Jenis file',
	'attribute_type_explain' => 'Masukkan daftar ekstensi file yang diijinkan, dipisahkan dengan koma. Kosongkan untuk mengijinkan semua jenis file.',

	// images
	'attributes_size_width' => 'Lebar gambar maksimal',
	'attributes_size_width_explain' => 'Gambar akan disesuaikan ukurannya jika lebarnya melebihi batas ini',

	'attributes_size_height' => 'Tinggi gambar maksimal',
	'attributes_size_height_explain' => 'Gambar akan disesuaikan ukurannya jika tingginya melebihi batas ini',

	// custom vars
	'name' => 'Nama',
	'name_explain' => 'Nama unik untuk variabel ini',
	'name_missing' => 'Harap masukkan nama',
	'name_exists' => 'Nama sudah digunakan',

	'value' => 'Nilai',
	'value_explain' => 'Data yang ingin anda simpan (sampai 64kb)',
	'value_code_snipet' => 'Contoh untuk dimasukkan ke kode anda:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

	// messages
	'variable_created' => 'Variable telah berhasil dibuat',
	'variable_updated' => 'Variable telah berhasil diperbarui',
	'variable_deleted' => 'Variable telah berhasil dihapus',

	'field_created' => 'Field telah berhasil dibuat',
	'field_updated' => 'Field telah berhasil diperbarui',
	'field_deleted' => 'Field telah berhasil dihapus'

);