<?php

namespace Anchorcms\Forms\Installer;

class Database extends \Forms\Form
{

    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));
        
        $this->addElement(new \Forms\Elements\Select('db_driver', [
            'label' => 'Database Driver',
            'value' => 'sqlite',
            'options' => [
                'pdo_sqlite' => 'SQLite',
                'pdo_mysql' => 'MySQL',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Input('db_host', [
            'label' => 'Hostname',
            'value' => '127.0.0.1',
        ]));

        $this->addElement(new \Forms\Elements\Input('db_port', [
            'label' => 'Port',
            'value' => '3306',
        ]));

        $this->addElement(new \Forms\Elements\Input('db_user', [
            'label' => 'Username',
            'value' => 'root',
        ]));

        $this->addElement(new \Forms\Elements\Input('db_password', [
            'label' => 'Password',
        ]));

        $this->addElement(new \Forms\Elements\Input('db_dbname', [
            'label' => 'Database Name',
            'value' => 'anchor',
        ]));

        $this->addElement(new \Forms\Elements\Input('db_path', [
            'label' => 'Database Path',
            'value' => 'anchor.sqlite',
        ]));

        $this->addElement(new \Forms\Elements\Input('db_table_prefix', [
            'label' => 'Table Prefix',
            'value' => 'anchor_',
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Next',
            'attributes' => ['class' => 'button button--primary float--right'],
        ]));
    }

    public function getFilters()
    {
        return [
            'db_driver' => FILTER_SANITIZE_STRING,
            'db_host' => FILTER_SANITIZE_STRING,
            'db_port' => FILTER_SANITIZE_STRING,
            'db_user' => FILTER_SANITIZE_STRING,
            'db_password' => FILTER_UNSAFE_RAW,
            'db_dbname' => FILTER_SANITIZE_STRING,
            'db_path' => FILTER_SANITIZE_STRING,
            'db_table_prefix' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules()
    {
        return [
            'db_driver' => ['required'],
        ];
    }
}
