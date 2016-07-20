<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Paginator;
use Anchorcms\Filters;
use Anchorcms\Forms\Category as CategoryForm;
use Anchorcms\Forms\ValidateToken;
use Validation\ValidatorFactory;

class Categories extends AbstractController
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

        $total = $this->container['mappers.categories']->count();
        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $offset = ($input['page'] - 1) * $perpage;

        $query = $this->container['mappers.categories']->query()
            ->orderBy('title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($perpage);

        $categories = $this->container['mappers.categories']->fetchAll($query);

        $paging = new Paginator($this->container['url']->to('/admin/categories'), $input['page'], $total, $perpage);

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
        $form = new CategoryForm;
        $form->init();

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        $slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);

        if ($validator->isValid()) {
            $query = $this->container['mappers.categories']->query()
                ->andWhere('slug = :slug')
                ->setParameter('slug', $slug);

            if ($this->container['mappers.categories']->count($query)) {
                $validator->setInvalid('slug already used');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to('/admin/categories/create'));
        }

        $id = $this->container['mappers.categories']->insert([
            'title' => $input['title'],
            'slug' => $slug,
            'description' => $input['description'],
        ]);

        $this->container['messages']->success(['Category created']);

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

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());

        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');

        $slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);

        if ($validator->isValid()) {
            $query = $this->container['mappers.categories']->query()
                ->andWhere('slug = :slug')
                ->setParameter('slug', $slug)
                ->andWhere('id <> :id')
                ->setParameter('id', $category->id);

            if ($this->container['mappers.categories']->count($query)) {
                $validator->setInvalid('slug already used');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to(sprintf('/admin/categories/%d/edit', $category->id)));
        }

        $this->container['mappers.categories']->update($category->id, [
            'title' => $input['title'],
            'slug' => $slug,
            'description' => $input['description'],
        ]);

        $this->container['messages']->success(['Category updated']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/categories/%d/edit', $category->id)));
    }

    public function getDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $category = $this->container['mappers.categories']->id($args['id']);

        if (!$category) {
            return $this->redirect($response, $this->container['url']->to('/admin/categories'));
        }

        $query = $this->container['mappers.categories']->query()
            ->where('id <> :id')
            ->setParameter('id', $category->id);
        $remaining = $this->container['mappers.categories']->count($query);

        // you cant delete all the categories
        if ($remaining < 1) {
            $this->container['messages']->error(['You cannot delete all categories']);
            return $this->redirect($response, $this->container['url']->to('/admin/categories'));
        }

        // first category to move posts to
        $newCategory = $this->container['mappers.categories']->fetch($query);

        // move posts to another category
        $this->container['db']->update($this->container['mappers.posts']->getTableName(), [
            'category' => $newCategory->id,
        ], [
            'category' => $category->id,
        ]);

        // delete category now its empty
        $this->container['mappers.categories']->delete($category->id);

        $this->container['messages']->success(['Category deleted']);
        return $this->redirect($response, $this->container['url']->to('/admin/categories'));
    }
}
