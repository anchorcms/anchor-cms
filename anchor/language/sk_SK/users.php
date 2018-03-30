<?php

return [

    'users' => 'Užívatelia',

    'create_user'           => 'Vytvoriť nového užívateľa',
    'add_user'              => 'Pridať nového užívateľa',
    'editing_user'          => 'Upraviť %s&rsquo;s Profil',
    'remembered'            => 'Poznám svoje heslo',
    'forgotten_password'    => 'Zabudli ste heslo?',

    // roles
    'administrator'         => 'Administrátor',
    'administrator_explain' => '',

    'editor'         => 'Redaktor',
    'editor_explain' => '',

    'user'              => 'Užívateľ',
    'user_explain'      => '',

    // form fields
    'real_name'         => 'Skutočné meno',
    'real_name_explain' => '',

    'bio'         => 'Biografia',
    'bio_explain' => '',

    'status'         => 'Stav',
    'status_explain' => '',

    'role'         => 'Rola',
    'role_explain' => '',

    'username'         => 'Užívateľské meno',
    'username_explain' => '',
    'username_missing' => 'Vyplňte užívateľské meno, musí obsahovať aspoň %s znaky(ov)',

    'password'           => 'Heslo',
    'password_explain'   => '',
    'password_too_short' => 'Heslo musí obsahovať aspoň %s znaky(ov)',

    'new_password' => 'Nové heslo',

    'email'            => 'E-mail',
    'email_explain'    => '',
    'email_missing'    => 'Vyplňte platnú e-mailovú adresu',
    'email_not_found'  => 'Profil sa nenašiel.',

    // messages
    'updated'          => 'Užívateľský profil bol aktualizovaný.',
    'created'          => 'Užívateľský profil bol vytvorený.',
    'deleted'          => 'Užívateľský profil bol vymazaný.',
    'delete_error'     => 'Nemôžete zmazať sami seba',
    'login_error'      => 'Užívateľské meno alebo heslo je nesprávne.',
    'logout_notice'    => 'Práve ste boli odhlásený.',
    'recovery_sent'    => 'Odoslali sme Vám e-mail pre potvrdenie obnovy Vásho hesla.',
    'recovery_expired' => 'Token k obnove hesla expiroval. Skúste obnoviť heslo znovu.',
    'password_reset'   => 'Vaše nové heslo bolo nastavené. Teraz sa môžete prihlásiť!',

    // password recovery email
    'recovery_subject' => 'Obnova hesla',
    'recovery_message' => 'Požiadali ste o obnovu Vášho hesla.' .
                          'K obnove hesla kliknite na nasledujúci link.' . PHP_EOL . '%s',

];
