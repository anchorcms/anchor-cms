<?php

namespace Anchorcms\Controllers\Admin;

class Vars extends Backend
{

    protected $prefix = 'global_';

    public function getIndex()
    {
        $meta = $this->container['mappers.meta']
            ->where('key', 'LIKE', $this->prefix . '%')
            ->sort('key', 'asc')
            ->get();

        $vars['title'] = 'Global Variables';
        $vars['metadata'] = $meta;

        return $this->renderTemplate('layouts/default', 'vars/index', $vars);
    }

    public function getCreate()
    {
        $form = new \Forms\CustomVars([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/vars/save'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // re-populate submitted data
        $form->setValues($this->container['session']->getFlash('input', []));

        $vars['title'] = 'Creating a new custom variable';
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'vars/create', $vars);
    }

    public function postSave()
    {
        $form = new \Forms\CustomVars;
        $form->init();

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        $key = strtolower($input['key']);

        $key = preg_replace('#\W+#', '_', $key);

        $key = $this->prefix.$key;

        if ($validator->isValid()) {
            $exists = $this->container['mappers.meta']
                ->where('key', '=', $key)
                ->count();

            if ($exists) {
                $validator->setInvalid('Key already exists');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putFlash('input', $input);
            return $this->redirect($this->container['url']->to('/admin/vars/create'));
        }

        $id = $this->container['mappers.meta']->insert([
            'key' => $key,
            'value' => $input['value'],
        ]);

        $this->container['messages']->success(['Custom variable created']);
        return $this->redirect($this->container['url']->to(sprintf('/admin/vars/%d/edit', $id)));
    }

    public function getEdit($request)
    {
        $id = $request->getAttribute('id');
        $meta = $this->container['mappers.meta']->where('id', '=', $id)->fetch();

        $form = new \Forms\CustomVars([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/vars/%s/update', $meta->id)),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // cannot change key
        $form->getElement('key')->setAttribute('readonly', 'readonly');

        // set default values from post
        $form->setValues($meta->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getFlash('input', []));

        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $meta->key);
        $vars['form'] = $form;
        $vars['meta'] = $meta;

        return $this->renderTemplate('layouts/default', 'vars/edit', $vars);
    }

    public function postUpdate($request)
    {
        $id = $request->getAttribute('id');
        $meta = $this->container['mappers.meta']->where('id', '=', $id)->fetch();

        $form = new \Forms\CustomVars;
        $form->init();

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putFlash('input', $input);
            return $this->redirect($this->container['url']->to(sprintf('/admin/vars/%s/edit', $meta->id)));
        }

        $this->container['mappers.meta']->where('id', '=', $meta->id)->update([
            'value' => $input['value'],
        ]);

        $this->container['messages']->success(['Custom variable updated']);
        return $this->redirect($this->container['url']->to(sprintf('/admin/vars/%s/edit', $meta->id)));
    }

    public function getDelete($request)
    {
        $id = $request->getAttribute('id');
        $meta = $this->container['mappers.meta']->where('id', '=', $id)->fetch();

        if (! $meta) {
            return $this->redirect($this->container['url']->to('/admin/vars'));
        }

        $this->container['mappers.meta']->where('id', '=', $meta->id)->delete();

        $this->container['messages']->success(['Global variable deleted']);
        return $this->redirect($this->container['url']->to('/admin/vars'));
    }
}
