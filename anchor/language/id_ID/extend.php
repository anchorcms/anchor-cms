<?php

return array(

    'extend' => 'Perluasan',

    'fields' => 'Bidang Tambahan',
    'fields_desc' => 'Buat bidang tambahan.',

    'pagetypes' => 'Jenis Halaman Situs',
    'pagetypes_desc' => 'Buat jenis halaman yang berbeda.',

    'variables' => 'Variabel Situs',
    'variables_desc' => 'Buat metadata tambahan.',

    'create_field' => 'Buat bidang baru',
    'editing_custom_field' => 'Menyunting bidang &ldquo;%s&rdquo;',
    'nofields_desc' => 'Belum ada bidang.',

    'create_variable' => 'Buat variabel baru',
    'editing_variable' => 'Menyunting variabel &ldquo;%s&rdquo;',
    'novars_desc' => 'Belum ada variabel.',

    'create_pagetype' => 'Buat jenis halaman baru',
    'editing_pagetype' => 'Menyunting jenis halaman &ldquo;%s&rdquo;',

    // form fields
    'type' => 'Jenis',
    'type_explain' => 'Jenis konten yang ingin Anda tambahkan bidang.',
    'notypes_desc' => 'Belum ada jenis halaman.',

    'pagetype' => 'Jenis halaman',
    'pagetype_explain' => 'Jenis halaman yang ingin Anda tambahkan bidang.',

    'field' => 'Bidang',
    'field_explain' => 'Jenis masukan HTML.',

    'key' => 'Kunci unik',
    'key_explain' => 'Kunci unik untuk bidang Anda.',
    'key_missing' => 'Mohon masukkan kunci unik.',
    'key_exists' => 'Kunci sudah terpakai.',

    'label' => 'Label',
    'label_explain' => 'Nama label untuk ditampilkan di antarmuka.',
    'label_missing' => 'Mohon masukkan label.',

    'attribute_type' => 'Jenis berkas',
    'attribute_type_explain' => 'Daftar jenis berkas yang diizinkan (dipisahkan dengan tanda koma), kosongkan untuk mengizinkan semua jenis berkas.',

    // images
    'attributes_size_width' => 'Lebar maksimal gambar',
    'attributes_size_width_explain' => 'Gambar akan diperkecil jika melebihi lebar maksimal.',

    'attributes_size_height' => 'Tinggi maksimal gambar',
    'attributes_size_height_explain' => 'Gambar akan diperkecil jika melebihi tinggi maksimal.',

    // custom vars
    'name' => 'Nama',
    'name_explain' => 'Nama unik untuk variabel Anda.',
    'name_missing' => 'Mohon masukkan nama unik.',
    'name_exists' => 'Nama sudah terpakai.',

    'value' => 'Nilai',
    'value_explain' => 'Data yang ingin Anda simpan (sampai 64kb).',
    'value_code_snipet' => 'Potongan untuk menyisipkan ke dalam template Anda:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

    // messages
    'variable_created' => 'Variabel Anda telah ditambahkan.',
    'variable_updated' => 'Variabel Anda telah diperbaharui.',
    'variable_deleted' => 'Variabel Anda telah dihapus.',

    'pagetype_created' => 'Jenis halaman Anda telah ditambahkan.',
    'pagetype_updated' => 'Jenis halaman Anda telah diperbaharui.',
    'pagetype_deleted' => 'Jenis halaman Anda telah dihapus.',

    'field_created' => 'Bidang Anda telah ditambahkan.',
    'field_updated' => 'Bidang Anda telah diperbaharui.',
    'field_deleted' => 'Bidang Anda telah dihapus.'

);
