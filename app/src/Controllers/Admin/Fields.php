<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Filters;
use Anchorcms\Forms\CustomField as CustomFieldForm;
use Anchorcms\Forms\ValidateToken;
use Validation\ValidatorFactory;
use Validation\Validator;
use Forms\Form;

class Fields extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $query = $this->container['mappers.customFields']->query();
        $fields = $this->container['mappers.customFields']->fetchAll($query);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Custom Fields';
        $vars['fields'] = $fields;

        $this->renderTemplate($response, 'layouts/default', 'fields/index', $vars);

        return $response;
    }

    public function getCreate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->getForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/fields/save'),
        ]);
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // re-populate submitted data
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Creating a new custom field';
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'fields/create', $vars);

        return $response;
    }

    public function postSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->getForm();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = $this->getValidator($input, $form);

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to('/admin/fields/create'));
        }

        $id = $this->container['mappers.customFields']->insert([
            'type' => $input['type'],
            'field' => $input['field'],
            'key' => $input['key'],
            'label' => $input['label'],
            'attributes' => '{}',
        ]);

        $this->container['messages']->success(['Custom field created']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/fields/%d/edit', $id)));
    }

    public function getEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $field = $this->container['mappers.customFields']->fetchByAttribute('id', $args['id']);

        $form = $this->getForm([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/fields/%d/update', $field->id)),
        ]);
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // set default values from post
        $form->setValues($field->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $field->label);
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'fields/edit', $vars);

        return $response;
    }

    public function postUpdate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $field = $this->container['mappers.customFields']->fetchByAttribute('id', $args['id']);

        $form = $this->getForm();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = $this->getValidator($input, $form);

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($this->container['url']->to(sprintf('/admin/fields/%d/edit', $post->id)));
        }

        $this->container['mappers.customFields']->update($field->id, [
            'type' => $input['type'],
            'field' => $input['field'],
            'key' => $input['key'],
            'label' => $input['label'],
        ]);

        $this->container['messages']->success(['Custom field updated']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/fields/%d/edit', $field->id)));
    }

    protected function getValidator(array $input, Form $form): Validator
    {
        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');
        return $validator;
    }

    protected function getForm(array $attributes = []): Form
    {
        $form = new CustomFieldForm($attributes);
        $form->init();
        return $form;
    }
}
