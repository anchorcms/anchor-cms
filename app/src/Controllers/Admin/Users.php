<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Paginator;
use Anchorcms\Filters;
use Anchorcms\Forms\User as UserForm;
use Anchorcms\Forms\ValidateToken;
use Validation\ValidatorFactory;

class Users extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $input = Filters::withDefaults($request->getQueryParams(), [
            'page' => [
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_SCALAR,
                'options' => [
                    'default' => 1,
                    'min_range' => 1,
                ],
            ],
        ]);

        $total = $this->container['mappers.users']->count();
        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $offset = ($input['page'] - 1) * $perpage;

        $query = $this->container['mappers.users']->query();

        $query->orderBy('name', 'ASC')
            ->setMaxResults($perpage)
            ->setFirstResult($offset);

        $users = $this->container['mappers.users']->fetchAll($query);

        $paging = new Paginator($this->container['url']->to('/admin/users'), $input['page'], $total, $perpage);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Users';
        $vars['users'] = $users;
        $vars['paging'] = $paging;

        $this->renderTemplate($response, 'layouts/default', 'users/index', $vars);

        return $response;
    }

    public function getCreate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new UserForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/users/save'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Creating a new user';
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'users/create', $vars);

        return $response;
    }

    public function postSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new UserForm;
        $form->init();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if ($validator->isValid()) {
            $user = $this->container['mappers.users']->fetchByUsername($input['username']);

            if ($user) {
                $validator->setInvalid('Username already taken');
            }

            $user = $this->container['mappers.users']->fetchByEmail($input['email']);

            if ($user) {
                $validator->setInvalid('Email address already in use');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to('/admin/users/create'));
        }

        $password = $this->container['services.auth']->hashPassword($input['password']);

        $id = $this->container['mappers.users']->insert([
            'username' => $input['username'],
            'password' => $password,
            'email' => $input['email'],
            'name' => $input['name'],
            'bio' => $input['bio'],
            'status' => $input['status'],
            'role' => $input['role'],
        ]);

        $this->container['messages']->success(['User created']);
        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/users/%d/edit', $id)));
    }

    public function getEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->container['mappers.users']->fetchById($args['id']);

        $form = new UserForm([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/users/%d/update', $user->id)),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // set default values from post
        $form->setValues($user->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $user->name);
        $vars['user'] = $user;
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'users/edit', $vars);

        return $response;
    }

    public function postUpdate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->container['mappers.users']->fetchById($args['id']);

        $form = new UserForm;
        $form->init();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $rules = $form->getRules();

        // no password change so no validation
        if (empty($input['password'])) {
            unset($rules['password']);
        }

        $validator = ValidatorFactory::create($input, $rules);

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        // make sure its a good password
        if (! empty($input['password']) && $validator->isValid()) {
            $validator->addRule(function ($value) {
                $strength = $this->container['zxcvbn']->passwordStrength($value);
                return [$strength['score'] > 0, 'Get out of here! Choose a better password'];
            }, 'password');
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to(sprintf('/admin/users/%d/edit', $user->id)));
        }

        $update = [
            'username' => $input['username'],
            'email' => $input['email'],
            'name' => $input['name'],
            'bio' => $input['bio'],
            'status' => $input['status'],
            'role' => $input['role'],
        ];

        // password set, hash it and add it to update array
        if ($input['password']) {
            $update['password'] = $this->container['services.auth']->hashPassword($input['password']);
        }

        $this->container['mappers.users']->update($user->id, $update);

        $this->container['messages']->success(['User updated']);
        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/users/%d/edit', $user->id)));
    }
}
