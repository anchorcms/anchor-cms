<?php

return [

    'extend' => 'Rozšíriť',

    'fields'      => 'Vlastné polia',
    'fields_desc' => 'Vytváranie doplňujúcich polí',

    'pagetypes'      => 'Typy web stránok',
    'pagetypes_desc' => 'Vytváranie rôznych typov web stránok',

    'variables'      => 'Premenné webu',
    'variables_desc' => 'Vytváranie doplňujúcich metadát',

    'create_field'         => 'Vytvoriť nové pole',
    'editing_custom_field' => 'Upraviť pole &ldquo;%s&rdquo;',
    'nofields_desc'        => 'Zatiaľ neboli vytvorené žiadne polia',

    'create_variable'  => 'Vytvoriť novú premennú',
    'editing_variable' => 'Upraviť premennú &ldquo;%s&rdquo;',
    'novars_desc'      => 'Zatiaľ neboli vytvorené žiadne premenné',

    'create_pagetype'  => 'Vytvoriť nový typ web stránky',
    'editing_pagetype' => 'Upraviť typ web stránky &ldquo;%s&rdquo;',

    // form fields
    'type'             => 'Typ',
    'type_explain'     => 'Typ obsahu, ktorý chcete pridať do tohto pola.',
    'notypes_desc'     => 'Zatiaľ neboli vytvorené žiadne typy web stránok',

    'pagetype'         => 'Typ web stránky',
    'pagetype_explain' => 'Typ web stránky, ktorý chcete pridať do tohto pola.',

    'field'         => 'Pole',
    'field_explain' => 'Html input typ',

    'key'         => 'Unikátny kľúč',
    'key_explain' => 'Unikátny kľúč pre Vaše pole',
    'key_missing' => 'Vyplňte unikátny kľúč',
    'key_exists'  => 'Kľúč už existuje',

    'label'         => 'Štítok',
    'label_explain' => 'Čitateľný názov Vášho pola',
    'label_missing' => 'Vyplňte štítok',

    'attribute_type'                => 'Typy súborov',
    'attribute_type_explain'        => 'Čiarkami oddelený zoznam povolených typov súborov, nechajte prázdne pre povolenie všetkých typov.',

    // images
    'attributes_size_width'         => 'Maxmálna šírka obrázkov',
    'attributes_size_width_explain' => 'Obrázky budú automaticky zmenšené ak presahujú maximálnu šírku',

    'attributes_size_height'         => 'Maximálna výška obrázkov',
    'attributes_size_height_explain' => 'Obrázky budú automaticky zmenšené ak presahujú maximálnu výsku',

    // custom vars
    'name'                           => 'Názov',
    'name_explain'                   => 'Unikátny názov',
    'name_missing'                   => 'Vyplňte unikátny názov',
    'name_exists'                    => 'Tento názov sa už používa',

    'value'             => 'Hodnota',
    'value_explain'     => 'Dáta, ktoré chcete uložiť (maximálne 64kb)',
    'value_code_snipet' => 'Kus kódu pre vloženie do Vašej šablóny:<br>
		<code>' . e('<?php echo site_meta(\'%s\'); ?>') . '</code>',

    // messages
    'variable_created'  => 'Premenná bola vytvorená',
    'variable_updated'  => 'Premenná bola aktualizovaná',
    'variable_deleted'  => 'Premenná bola vymazaná',

    'pagetype_created' => 'Typ web stránky bol vytvorený',
    'pagetype_updated' => 'Typ web stránky bol aktualizovaný',
    'pagetype_deleted' => 'Typ web stránky bol vymazaný',

    'field_created' => 'Pole bolo vytvorené',
    'field_updated' => 'Pole bolo aktualizované',
    'field_deleted' => 'Pole bolo vymazané'

];
