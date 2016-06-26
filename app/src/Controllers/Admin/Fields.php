<?php

namespace Anchorcms\Controllers\Admin;

use Anchorcms\Controllers\AbstractController;

class Fields extends AbstractController
{
    public function getIndex()
    {
        $input = filter_var_array($_GET, [
            'page' => FILTER_SANITIZE_NUMBER_INT,
        ]);

        $total = $this->container['mappers.customFields']->count();

        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $fields = $this->container['mappers.customFields']->sort('key', 'asc')->take($perpage);

        if ($input['page']) {
            $offset = ($input['page'] - 1) * $perpage;
            $fields->skip($offset);
        }

        $paging = new \Paginator($this->container['url']->to('/admin/fields'), $input['page'], $total, $perpage, $input);

        $vars['title'] = 'Custom Fields';
        $vars['fields'] = $fields->get();
        $vars['paging'] = $paging;

        return $this->renderTemplate('layouts/default', 'fields/index', $vars);
    }

    public function getCreate()
    {
        $form = new \Forms\CustomField([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/fields/save'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // re-populate submitted data
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['title'] = 'Creating a new custom field';
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'fields/create', $vars);
    }

    public function postSave()
    {
        $form = new \Forms\CustomField;
        $form->init();

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);
            return $this->redirect($this->container['url']->to('/admin/fields/create'));
        }

        $id = $this->container['mappers.customFields']->insert([
            'type' => $input['type'],
            'field' => $input['field'],
            'key' => $input['key'],
            'label' => $input['label'],
            'attributes' => '{}',
        ]);

        $this->container['messages']->success('Custom field created');
        return $this->redirect($this->container['url']->to(sprintf('/admin/fields/%d/edit', $id)));
    }

    public function getEdit($request)
    {
        $id = $request->getAttribute('id');
        $field = $this->container['mappers.customFields']->where('id', '=', $id)->fetch();

        $form = new \Forms\CustomField([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/fields/%d/update', $field->id)),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // set default values from post
        $form->setValues($field->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $field->label);
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'fields/edit', $vars);
    }

    public function postUpdate($request)
    {
        $id = $request->getAttribute('id');
        $field = $this->container['mappers.customFields']->where('id', '=', $id)->fetch();

        $form = new \Forms\CustomField;
        $form->init();

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);
            return $this->redirect($this->container['url']->to(sprintf('/admin/fields/%d/edit', $post->id)));
        }

        $this->container['mappers.customFields']->where('id', '=', $field->id)->update([
            'type' => $input['type'],
            'field' => $input['field'],
            'key' => $input['key'],
            'label' => $input['label'],
        ]);

        $this->container['messages']->success('Custom field updated');
        return $this->redirect($this->container['url']->to(sprintf('/admin/fields/%d/edit', $id)));
    }
}
