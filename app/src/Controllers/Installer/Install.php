<?php

namespace Anchorcms\Controllers\Installer;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Filters;
use Anchorcms\Forms\ValidateToken;
use Anchorcms\Forms\Installer\L10n as L10nForm;
use Anchorcms\Forms\Installer\Database as DatabaseForm;
use Anchorcms\Forms\Installer\Metadata as MetadataForm;
use Anchorcms\Forms\Installer\Account as AccountForm;
use Validation\ValidatorFactory;

class Install extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->redirect($response, '/l10n');
    }

    public function getL10n(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new L10nForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/l10n'),
            'autocomplete' => 'off',
        ]);
        $form->init();

        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // populate from session
        $data = $this->container['session']->get('install', [
            'app_timezone' => date_default_timezone_get(),
        ]);
        $form->setValues($data);

        $vars['title'] = 'Installin\' Anchor CMS';
        $vars['form'] = $form;

        $this->renderTemplate($response, 'installer/layout', 'installer/l10n', $vars);

        return $response;
    }

    public function postL10n(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new L10nForm;

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());

        $data = $this->container['session']->get('install', []);
        $this->container['session']->put('install', array_merge($data, $input));

        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());

            return $this->redirect($response, '/l10n');
        }

        return $this->redirect($response, '/database');
    }

    public function getDatabase(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new DatabaseForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/database'),
            'autocomplete' => 'off',
        ]);
        $form->init();

        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // populate from session
        $data = $this->container['session']->get('install', []);
        $form->setValues($data);

        $vars['title'] = 'Installin\' Anchor CMS';
        $vars['form'] = $form;
        $vars['backUrl'] = $this->container['url']->to('/l10n');

        $this->renderTemplate($response, 'installer/layout', 'installer/database', $vars);

        return $response;
    }

    public function postDatabase(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new DatabaseForm;

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());

        $data = $this->container['session']->get('install', []);
        $this->container['session']->put('install', array_merge($data, $input));

        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        try {
            $this->container['services.installer']->getDatabaseConnection($input);
        } catch (\Throwable $e) {
            $validator->setInvalid($e->getMessage());
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());

            return $this->redirect($response, '/database');
        }

        return $this->redirect($response, '/metadata');
    }

    public function getMetadata(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new MetadataForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/metadata'),
            'autocomplete' => 'off',
        ]);
        $form->init();

        $form->getElement('_token')->setValue($this->container['csrf']->token());
        $form->getElement('site_path')->setValue('/');

        // populate from session
        $data = $this->container['session']->get('install', []);
        $form->setValues($data);

        $vars['title'] = 'Installin\' Anchor CMS';
        $vars['form'] = $form;
        $vars['backUrl'] = $this->container['url']->to('/database');

        $this->renderTemplate($response, 'installer/layout', 'installer/metadata', $vars);

        return $response;
    }

    public function postMetadata(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new MetadataForm;

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());

        $data = $this->container['session']->get('install', []);
        $this->container['session']->put('install', array_merge($data, $input));

        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());

            return $this->redirect($response, '/metadata');
        }

        return $this->redirect($response, '/account');
    }

    public function getAccount(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new AccountForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/account'),
            'autocomplete' => 'off',
        ]);
        $form->init();

        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // populate from session
        $data = $this->container['session']->get('install', []);
        $form->setValues($data);

        $vars['title'] = 'Installin\' Anchor CMS';
        $vars['form'] = $form;
        $vars['backUrl'] = $this->container['url']->to('/metadata');

        $this->renderTemplate($response, 'installer/layout', 'installer/account', $vars);

        return $response;
    }

    public function postAccount(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new AccountForm;

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        $data = $this->container['session']->get('install', []);
        $this->container['session']->put('install', array_merge($data, $input));

        $validator->addRule(function ($value) {
            $strength = $this->container['zxcvbn']->passwordStrength($value);
            return [$strength['score'] > 0, 'Get out of here! Choose a better password'];
        }, 'account_password');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());

            return $this->redirect($response, '/account');
        }

        $data = $this->container['session']->get('install', []);
        // defer construction of auth service until the config files have
        // been written and the database has been created
        $this->container['services.installer']->run($data, $this->container['services.auth']);

        $this->container['session']->remove('install');

        return $this->redirect($response, '/');
    }
}
