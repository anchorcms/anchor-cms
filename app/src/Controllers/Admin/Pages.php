<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Paginator;
use Anchorcms\Filters;

class Pages extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $input = Filters::withDefaults($request->getQueryParams(), [
            'page' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'default' => 1,
                    'min_range' => 1,
                ],
            ],
            'status' => FILTER_SANITIZE_STRING,
        ]);

        $query = $this->container['mappers.pages']->query();

        $statuses = [
            'published' => 'Published',
            'draft' => 'Draft',
            'archived' => 'Archived',
        ];

        if (array_key_exists($input['status'], $statuses)) {
            $query->where('status = :status')
                ->setParameter('status', $input['status']);
        }

        $total = $this->container['mappers.pages']->count(clone $query);
        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $offset = ($input['page'] - 1) * $perpage;

        $query->orderBy('title', 'ASC')
            ->setMaxResults($perpage)
            ->setFirstResult($offset);

        $pages = $this->container['mappers.pages']->fetchAll($query);

        $paging = new Paginator($this->container['url']->to('/admin/pages'), $input['page'], $total, $perpage, $input);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Pages';
        $vars['statuses'] = $statuses;
        $vars['pages'] = $pages;
        $vars['paging'] = $paging;

        $this->renderTemplate($response, 'layouts/default', 'pages/index', $vars);

        return $response;
    }

    public function getCreate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new \Forms\Page([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/pages/save'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());
        $form->getElement('parent')->setOptions($this->container['mappers.pages']->dropdownOptions());

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'page');

        // re-populate submitted data
        $form->setValues($this->container['session']->getStash('input', []));

        $element = $form->getElement('show_in_menu');

        if ($element->getValue()) {
            $element->setChecked();
        }

        $element->setValue(1);

        $vars['title'] = 'Creating a new page';
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'pages/create', $vars);
    }

    public function postSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new \Forms\Page();
        $form->init();

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'page');

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($this->container['url']->to('/admin/pages/create'));
        }

        $slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);
        $html = $this->container['markdown']->convertToHtml($input['content']);

        $id = $this->container['mappers.pages']->insert([
            'parent' => $input['parent'],
            'slug' => $slug,
            'name' => $input['name'] ?: $input['title'],
            'title' => $input['title'],
            'content' => $input['content'],
            'html' => $html,
            'status' => $input['status'],
            'redirect' => $input['redirect'],
            'show_in_menu' => $input['show_in_menu'] ? 1 : 0,
            'menu_order' => $input['menu_order'],
        ]);

        // save custom fields
        $this->container['services.customFields']->saveFields($request, $input, 'page', $id);

        $this->container['messages']->success('Page created');

        return $this->redirect($this->container['url']->to(sprintf('/admin/pages/%d/edit', $id)));
    }

    public function getEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $page = $this->container['mappers.pages']->where('id', '=', $id)->fetch();

        $form = new \Forms\Page([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/pages/%d/update', $page->id)),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());
        $form->getElement('parent')->setOptions($this->container['mappers.pages']->dropdownOptions([0 => 'None']));

        // set default values from post
        $form->setValues($page->toArray());

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'page');

        // get custom field values
        $form->setValues($this->container['services.customFields']->getFieldValues('page', $id));

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $element = $form->getElement('show_in_menu');

        if ($element->getValue()) {
            $element->setChecked();
        }

        $element->setValue(1);

        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $page->title);
        $vars['page'] = $page;
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'pages/edit', $vars);
    }

    public function postUpdate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $page = $this->container['mappers.pages']->where('id', '=', $id)->fetch();

        $form = new \Forms\Page();
        $form->init();

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'page');

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($this->container['url']->to(sprintf('/admin/pages/%d/edit', $page->id)));
        }

        $slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);
        $html = $this->container['markdown']->convertToHtml($input['content']);

        $this->container['mappers.pages']->where('id', '=', $page->id)->update([
            'parent' => $input['parent'],
            'slug' => $slug,
            'name' => $input['name'] ?: $input['title'],
            'title' => $input['title'],
            'content' => $input['content'],
            'html' => $html,
            'status' => $input['status'],
            'redirect' => $input['redirect'],
            'show_in_menu' => $input['show_in_menu'] ? 1 : 0,
            'menu_order' => $input['menu_order'],
        ]);

        // update custom fields
        $this->container['services.customFields']->saveFields($request, $input, 'post', $id);

        $this->container['messages']->success('Page updated');

        return $this->redirect($this->container['url']->to(sprintf('/admin/pages/%d/edit', $id)));
    }

    public function getDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $page = $this->container['mappers.pages']->where('id', '=', $id)->fetch();

        if (!$page) {
            return $this->redirect($this->container['url']->to('/admin/pages'));
        }

        $this->container['mappers.pages']->where('id', '=', $page->id)->delete();
        $this->container['mappers.pagemeta']->where('page', '=', $page->id)->delete();

        $this->container['messages']->success('Page deleted');

        return $this->redirect($this->container['url']->to('/admin/pages'));
    }
}
