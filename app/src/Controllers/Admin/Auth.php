<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Forms\Login;
use Anchorcms\Forms\Amnesia;
use Anchorcms\Forms\Reset;
use Anchorcms\Forms\ValidateToken;

class Auth extends AbstractController
{
    public function getStart(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if ($this->container['session']->has('user')) {
            $forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, [
                'options' => [
                    'default' => $this->container['url']->to('/admin/posts'),
                ],
            ]);

            return $this->redirect($response, $forward);
        }
        return $this->redirect($response, $this->container['url']->to('/admin/auth/login'));
    }

    public function getLogin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $vars['title'] = 'Login';

        $form = new Login([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/auth/attempt'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        $values = $this->container['session']->getStash('input', []);
        $form->setValues($values);

        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/minimal', 'users/login', $vars);

        return $response;
    }

    public function postAttempt(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new Login;
        $input = $form->getFilters();

        // validate input
        $validator = $this->container['validation']->create($input, $form->getRules());

        // check token
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if ($validator->isValid()) {
            $user = $this->container['services.auth']->login($input['username'], $input['password']);

            if (false === $user) {
                $validator->setInvalid('Sorry, we don&apos;t reconise those details');
            } elseif (false === $user->isActive()) {
                $validator->setInvalid('Your account have not active');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', ['username' => $input['username']]);

            return $this->redirect($response, $this->container['url']->to('/admin/auth/login'));
        }

        // check password and update it
        if ($this->container['services.auth']->checkPasswordHash($user->password)) {
            $this->container['services.auth']->changePassword($user, $input['password']);
        }

        // create session
        $this->container['session']->put('user', $user->id);

        // redirect
        $forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, [
            'options' => [
                'default' => $this->container['url']->to('/admin/posts'),
            ],
        ]);

        return $this->redirect($response, $forward);
    }

    public function getLogout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->container['session']->destroy()->migrate();
        $this->container['messages']->success('You are now logged out');
        return $this->redirect($response, $this->container['url']->to('/admin/auth/login'));
    }

    public function getAmnesia(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $vars['title'] = 'Forgotten Password';

        $form = new Amnesia([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/auth/amnesia'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        $values = $this->container['session']->getStash('input', []);
        $form->setValues($values);

        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/minimal', 'users/amnesia', $vars);

        return $response;
    }

    public function postAmnesia(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new Amnesia;
        $input = $form->getFilters();

        // validate input
        $validator = $this->container['validation']->create($input, $form->getRules());

        // check token
        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if ($validator->isValid()) {
            // check username
            $user = $this->container['mappers.users']->fetchByEmail($input['email']);

            if (false === $user) {
                $validator->setInvalid('Sorry, we don&apos;t reconise those details');
            }
            // is active
            elseif (false === $user->isActive()) {
                $validator->setInvalid('Your account have not active');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', ['email' => $input['email']]);

            return $this->redirect($response, $this->container['url']->to('/admin/auth/amnesia'));
        }

        $to = [$user->email => $user->name];
        $from = $this->container['config']->get('mail.from');
        $subject = sprintf('[%s] Password Reset', $this->container['mappers.meta']->key('sitename'));

        $token = $this->container['services.auth']->resetToken($user);

        $link = $this->container['url']->to('/admin/auth/reset?token=' . $token);
        $body = $this->renderTemplate('layouts/email', 'users/reset-password-email', ['link' => $link, 'user' => $user]);

        $this->container['services.postman']->deliver($to, $from, $subject, $body);

        $this->container['messages']->success(['We have sent a email with further instructions']);
        return $this->redirect($response, '/admin/auth/login');
    }

    public function getReset(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // check token
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
        $user = $this->container['services.auth']->verifyToken($token);

        if (! $user) {
            $this->container['messages']->error(['Invalid reset token']);
            return $this->redirect($response, $this->container['url']->to('/admin/auth/login'));
        }

        $vars['title'] = 'Reset Password';

        $form = new Reset([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/auth/reset?token=' . $token),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/minimal', 'users/reset', $vars);

        return $response;
    }

    public function postReset(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // check token
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
        $user = $this->container['services.auth']->verifyToken($token);

        if (! $user) {
            $this->container['messages']->error(['Invalid reset token']);
            return $this->redirect($response, $this->container['url']->to('/admin/auth/login'));
        }

        $form = new Reset;
        $input = $form->getFilters();

        // validate input
        $validator = $this->container['validation']->create($input, $form->getRules());

        // check token
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            return $this->redirect($response, $this->container['url']->to('/admin/auth/reset?token=' . $token));
        }

        $this->container['services.auth']->changePassword($user, $input['password']);

        $this->container['messages']->success(['Your password has been reset']);
        return $this->redirect($response, $this->container['url']->to('/admin/auth/login'));
    }
}
