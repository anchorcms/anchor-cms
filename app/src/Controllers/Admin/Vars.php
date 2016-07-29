<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Forms\ValidateToken;
use Anchorcms\Forms\CustomVars;
use Anchorcms\Filters;
use Validation\ValidatorFactory;
use Forms\FormInterface;

class Vars extends AbstractController
{
    protected $prefix = 'global_';

    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $query = $this->container['mappers.meta']->query()
            ->where('key LIKE :key')
            ->setParameter('key', $this->prefix.'%')
            ->orderBy('key', 'asc');
        $meta = $this->container['mappers.meta']->fetchAll($query);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Global Variables';
        $vars['metadata'] = $meta;

        $this->renderTemplate($response, 'layouts/default', 'vars/index', $vars);

        return $response;
    }

    public function getCreate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->getForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/vars/save'),
        ]);

        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // re-populate submitted data
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Creating a new custom variable';
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'vars/create', $vars);

        return $response;
    }

    public function postSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->getForm();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        $key = $this->prefix.$this->container['slugify']->slugify($input['key']);

        if ($validator->isValid()) {
            $exists = $this->container['mappers.meta']->key($key);

            if ($exists) {
                $validator->setInvalid('Key already exists');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to('/admin/vars/create'));
        }

        $id = $this->container['mappers.meta']->insert([
            'key' => $key,
            'value' => $input['value'],
        ]);

        $this->container['messages']->success(['Custom variable created']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/vars/%d/edit', $id)));
    }

    public function getEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $meta = $this->container['mappers.meta']->fetchByAttribute('id', $args['id']);

        $form = $this->getForm([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/vars/%s/update', $meta->id)),
        ]);

        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // cannot change key
        $form->getElement('key')->setAttribute('readonly', 'readonly');

        // set default values from post
        $form->setValues($meta->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $meta->key);
        $vars['form'] = $form;
        $vars['meta'] = $meta;

        $this->renderTemplate($response, 'layouts/default', 'vars/edit', $vars);

        return $response;
    }

    public function postUpdate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $meta = $this->container['mappers.meta']->fetchByAttribute('id', $args['id']);

        $form = $this->getForm();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($this->container['url']->to(sprintf('/admin/vars/%s/edit', $meta->id)));
        }

        $this->container['mappers.meta']->update($meta->id, [
            'value' => $input['value'],
        ]);

        $this->container['messages']->success(['Custom variable updated']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/vars/%s/edit', $meta->id)));
    }

    public function getDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $meta = $this->container['mappers.meta']->fetchByAttribute('id', $args['id']);

        if (!$meta) {
            return $this->redirect($this->container['url']->to('/admin/vars'));
        }

        $this->container['mappers.meta']->delete($meta->id);

        $this->container['messages']->success(['Global variable deleted']);

        return $this->redirect($response, $this->container['url']->to('/admin/vars'));
    }

    protected function getForm(array $params = []): FormInterface
    {
        $form = new CustomVars($params);
        $form->init();
        return $form;
    }
}
