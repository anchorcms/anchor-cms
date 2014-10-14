<?php

return array(
   'extend' => '&Eacute;tendez',

   'fields'      => 'Domaines',
   'fields_desc' => 'Cr&eacute;ez les domaines suppl&eacute;mentaires',

   'variables'      => 'Variables de site',
   'variables_desc' => 'Cr&eacute;ez des variables suppl&eacute;mentaires',

   'create_field'         => 'Cr&eacute;ez un nouveau domaine',
   'editing_custom_field' => 'Modifiez un domaine &ldquo;%s&rdquo;',
   'nofields_desc'        => 'Il n\'y a pas de domaines encore',

   'create_variable'  => 'Cr&eacute;ez une nouvelle variable',
   'editing_variable' => 'Modifiez une variable &ldquo;%s&rdquo;',
   'novars_desc'      => 'Il n\'y a pas de variables encore',

   // form fields
   'type'         => 'Type',
   'type_explain' => 'Le type de contenu que vous souhaitez que ce domaine soit associ&eacute;e &agrave;',

   'field'         => 'Domaine',
   'field_explain' => 'Le type de l\'entr&eacute;e ',

   'key'         => 'Cl&eacute; unique',
   'key_explain' => 'La cl&eacute; pour votre domaine',
   'key_missing' => 'Entrez une cl&eacute; unique',
   'key_exists'  => 'Cette cl&eacute; est d&eacute;j&agrave; en cours d\'utilisation',

   'label' => '&Eacute;tiquette',
   'label_explain' => 'Une &eacute;tiquette lisable pour votre domaine',
   'label_missing' => 'Entrez une &eacute;tiquette',

   'attribute_type'         => 'Type de fichier',
   'attribute_type_explain' => 'Une list des types de fichier acceptables séparés par les virgules, vide pour tous les types',

   // images
   'attributes_size_width'         => 'Largeur maximale de l\'image',
   'attributes_size_width_explain' => 'Les images seront redimmensionn&eacute;es si elles sont plus grandes que la largeur maximale',

   'attributes_size_height'         => 'Hauteur maximale',
   'attributes_size_height_explain' => 'Les images seront redimmensionn&eacute;es si elles sont plus grandes que la hauteur maximale',


   // custom vars
   'name'         => 'Nom',
   'name_explain' => 'Un nom unique',
   'name_missing' => 'Entrez un nom unique',
   'name_exists'  => 'Ce nom est d&eacute;j&agrave; en cours d\'utilisation',

   'value'             => 'Valeur',
   'value_explain'     => 'Les donn&eacute;es que vous souhaitez sauver (jusqu\'&agrave; 64kb)',
   'value_code_snipet' => 'Le fragment &agrave; ins&eacute;rer dan votre mod&egrave;le:<br>
      <code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

   // messages
   'variable_created' => 'Votre variable a &eacutet&eacute; cr&eacute;&eacute;e',
   'variable_updated' => 'Votre variable a &eacutet&eacute; mis &egrave; jour',
   'variable_deleted' => 'Votre variable a &eacutet&eacute; supprim&eacute;e', 

   'field_created' => 'Votre domaine a &eacutet&eacute; cr&eacute;&eacute;',
   'field_updated' => 'Votre domaine a &eacutet&eacute; mis &egrave; jour',
   'field_deleted' => 'Votre domaine a &eacutet&eacute; supprim&eacute;',
);

