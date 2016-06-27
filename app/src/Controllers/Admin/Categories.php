<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Paginator;
use Anchorcms\Forms\Category as CategoryForm;
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

    public function getCreate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new CategoryForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/categories/save'),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // re-populate submitted data
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Creating a new category';
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'categories/create', $vars);

        return $response;
    }

    public function postSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = new CategoryForm();
        $form->init();

        $input = filter_var_array($request->getParsedBody(), $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to('/admin/categories/create'));
        }

        $slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);

        $id = $this->container['mappers.categories']->insert([
            'title' => $input['title'],
            'slug' => $slug,
            'description' => $input['description'],
        ]);

        $this->container['messages']->success('Category created');

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/categories/%d/edit', $id)));
    }

    public function getEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $category = $this->container['mappers.categories']->id($args['id']);

        if (false === $category) {
            throw new \InvalidArgumentException(sprintf('category not found %d', $args['id']));
        }

        $form = new CategoryForm([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/categories/%d/update', $category->id)),
        ]);
        $form->init();
        $form->getElement('_token')->setValue($this->container['csrf']->token());

        // set default values from post
        $form->setValues($category->toArray());

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $category->title);
        $vars['category'] = $category;
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'categories/edit', $vars);

        return $response;
    }

    public function postUpdate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $category = $this->container['mappers.categories']->id($args['id']);

        if (false === $category) {
            throw new \InvalidArgumentException(sprintf('category not found %d', $args['id']));
        }

        $form = new CategoryForm();
        $form->init();

        $input = filter_var_array($request->getParsedBody(), $form->getFilters());
        $validator = $this->container['validation']->create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to(sprintf('/admin/categories/%d/edit', $post->id)));
        }

        $slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);

        $this->container['mappers.categories']->update($category->id, [
            'title' => $input['title'],
            'slug' => $slug,
            'description' => $input['description'],
        ]);

        $this->container['messages']->success('Category updated');

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/categories/%d/edit', $category->id)));
    }
}
