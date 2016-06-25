<?php

namespace Anchorcms\Controllers\Admin;

use Anchorcms\Controllers\AbstractController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Paginator;
use Anchorcms\Forms\Post as PostForm;
use Anchorcms\Forms\ValidateToken;

class Categories extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getQueryParams();
        $page = filter_var($params['page'] ?? 1, FILTER_SANITIZE_NUMBER_INT);

        $total = $this->container['mappers.categories']->count();
        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $offset = ($page - 1) * $perpage;

        $query = $this->container['mappers.categories']->query()->orderBy('title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($perpage);

        $categories = $this->container['mappers.categories']->fetchAll($query);

        $paging = new Paginator($this->container['url']->to('/admin/categories'), $page, $total, $perpage);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Categories';
        $vars['categories'] = $categories;
        $vars['paging'] = $paging;

        $this->renderTemplate($response, 'layouts/default', 'categories/index', $vars);

        return $response;
    }

    public function getCreate()
    {
        $form = new \Forms\Category([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/categories/save'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // re-populate submitted data
        $form->setValues($this->container['session']->getFlash('input', []));

        $vars['title'] = 'Creating a new category';
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'categories/create', $vars);
    }

    public function postSave()
    {
        $form = new \Forms\Category;
        $form->init();

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putFlash('input', $input);
            return $this->redirect($this->container['url']->to('/admin/categories/create'));
        }

        $slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);

        $id = $this->container['mappers.categories']->insert([
            'title' => $input['title'],
            'slug' => strtolower($slug),
            'description' => $input['description'],
        ]);

        $this->container['messages']->success('Category created');
        return $this->redirect($this->container['url']->to(sprintf('/admin/categories/%d/edit', $id)));
    }

    public function getEdit($request)
    {
        $id = $request->getAttribute('id');
        $category = $this->container['mappers.categories']->where('id', '=', $id)->fetch();

        $form = new \Forms\Category([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/categories/%d/update', $category->id))
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // set default values from post
        $form->setValues($category->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getFlash('input', []));

        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $category->title);
        $vars['category'] = $category;
        $vars['form'] = $form;

        return $this->renderTemplate('layouts/default', 'categories/edit', $vars);
    }

    public function postUpdate($request)
    {
        $id = $request->getAttribute('id');
        $category = $this->container['mappers.categories']->where('id', '=', $id)->fetch();

        $form = new \Forms\Category;
        $form->init();

        $input = filter_input_array(INPUT_POST, $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putFlash('input', $input);
            return $this->redirect($this->container['url']->to(sprintf('/admin/categories/%d/edit', $post->id)));
        }

        $slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);

        $this->container['mappers.categories']->where('id', '=', $category->id)->update([
            'title' => $input['title'],
            'slug' => strtolower($slug),
            'description' => $input['description'],
        ]);

        $this->container['messages']->success('Category updated');
        return $this->redirect($this->container['url']->to(sprintf('/admin/categories/%d/edit', $id)));
    }
}
