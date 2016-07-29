<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Paginator;
use Anchorcms\Filters;
use Anchorcms\Forms\Post as PostForm;
use Anchorcms\Forms\ValidateToken;
use Validation\ValidatorFactory;
use Validation\Validator;
use Forms\Form;

class Posts extends AbstractController
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
            'category' => FILTER_SANITIZE_NUMBER_INT,
            'status' => FILTER_SANITIZE_STRING,
            'search' => FILTER_SANITIZE_STRING,
        ]);

        $query = $this->container['mappers.posts']->query();

        if ($input['category']) {
            $query->andWhere('category = :category')
                ->setParameter('category', $input['category']);
        }

        if ($input['status']) {
            $query->andWhere('status = :status')
                ->setParameter('status', $input['status']);
        }

        if ($input['search']) {
            $query->andWhere('title LIKE :search')
                ->setParameter('search', '%'.$input['search'].'%');
        }

        $total = $this->container['mappers.posts']->count(clone $query);
        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $offset = ($input['page'] - 1) * $perpage;

        $query->orderBy('modified', 'DESC')
            ->setMaxResults($perpage)
            ->setFirstResult($offset);

        $posts = $this->container['mappers.posts']->fetchAll($query);

        $paging = Paginator::create($this->container['url']->to('/admin/posts'), $input['page'], ceil($total / $perpage), $input);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Posts';
        $vars['posts'] = $posts;
        $vars['paging'] = $paging;
        $vars['categories'] = $this->container['mappers.categories']->all();
        $vars['statuses'] = $this->container['services.posts']->getStatuses();
        $vars['filters'] = $input;

        $this->renderTemplate($response, 'layouts/default', 'posts/index', $vars);

        return $response;
    }

    public function getCreate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->getForm([
            'method' => 'post',
            'action' => $this->container['url']->to('/admin/posts/save'),
        ]);
        $form->get('_token')->setValue($this->container['csrf']->token());
        $form->get('category')->setOptions($this->container['mappers.categories']->dropdownOptions());

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'post');

        // re-populate submitted data
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Creating a new post';
        $vars['form'] = $form;

        $this->renderTemplate($response, 'layouts/default', 'posts/create', $vars);

        return $response;
    }

    public function postSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->getForm();

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'post');

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = $this->getValidator($input, $form);

        // check duplicate slug
        $slug = $this->container['slugify']->slugify(empty($input['slug']) ? $input['title'] : $input['slug']);

        if ($validator->isValid() && $input['status'] == 'published') {
            $query = $this->container['mappers.posts']->query()
                ->andWhere('slug = :slug')
                ->setParameter('slug', $slug)
                ->andWhere('status = :status')
                ->setParameter('status', $input['status']);

            if ($this->container['mappers.posts']->count($query)) {
                $validator->setInvalid('Post slug already exists');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to('/admin/posts/create'));
        }

        $now = date('Y-m-d H:i:s');
        $html = $this->container['markdown']->convertToHtml($input['content']);
        $user = $this->container['session']->get('user');

        $id = $this->container['mappers.posts']->insert([
            'author' => $user,
            'category' => $input['category'],
            'status' => $input['status'],

            'created' => $now,
            'modified' => $now,
            'published' => $input['published'] ?: $now,

            'title' => $input['title'],
            'slug' => $slug,

            'content' => $input['content'],
            'html' => $html,
        ]);

        // save custom fields
        $this->container['services.customFields']->saveFields($request, $input, 'post', $id);

        $this->container['messages']->success(['Post created']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/posts/%d/edit', $id)));
    }

    public function getEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'];
        $post = $this->container['mappers.posts']->id($id);

        $form = $this->getForm([
            'method' => 'post',
            'action' => $this->container['url']->to(sprintf('/admin/posts/%d/update', $post->id)),
        ]);
        $form->getElement('_token')->setValue($this->container['csrf']->token());
        $form->getElement('category')->setOptions($this->container['mappers.categories']->dropdownOptions());

        // set default values from post
        $form->setValues($post->toArray());

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'post');

        // get custom field values
        $form->setValues($this->container['services.customFields']->getFieldValues('post', $id));

        // re-populate old input
        $form->setValues($this->container['session']->getStash('input', []));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $post->title);
        $vars['form'] = $form;
        $vars['post'] = $post;

        $this->renderTemplate($response, 'layouts/default', 'posts/edit', $vars);

        return $response;
    }

    public function postUpdate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // get post to update
        $post = $this->container['mappers.posts']->id($args['id']);

        if (!$post) {
            throw new \InvalidArgumentException('post not found');
        }

        $form = $this->getForm();

        // append custom fields
        $this->container['services.customFields']->appendFields($form, 'post');

        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = $this->getValidator($input, $form);

        // check duplicate slug
        $slug = $this->container['slugify']->slugify(empty($input['slug']) ? $input['title'] : $input['slug']);

        if ($validator->isValid() && $input['status'] == 'published') {
            $query = $this->container['mappers.posts']->query()
                ->andWhere('slug = :slug')
                ->setParameter('slug', $slug)
                ->andWhere('status = :status')
                ->setParameter('status', $input['status'])
                ->andWhere('id <> :id')
                ->setParameter('id', $post->id);

            $duplicates = $this->container['mappers.posts']->count($query);

            if ($duplicates > 0) {
                $validator->setInvalid('Post slug already exists');
            }
        }

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());
            $this->container['session']->putStash('input', $input);

            return $this->redirect($response, $this->container['url']->to(sprintf('/admin/posts/%d/edit', $post->id)));
        }

        $now = date('Y-m-d H:i:s');
        $html = $this->container['markdown']->convertToHtml($input['content']);

        $this->container['mappers.posts']->update($post->id, [
            'category' => $input['category'],
            'status' => $input['status'],

            'modified' => $now,
            'published' => $input['published'] ?: $now,

            'title' => $input['title'],
            'slug' => strtolower($slug),

            'content' => $input['content'],
            'html' => $html,
        ]);

        // update custom fields
        $this->container['services.customFields']->saveFields($request, $input, 'post', $post->id);

        $this->container['messages']->success(['Post updated']);

        return $this->redirect($response, $this->container['url']->to(sprintf('/admin/posts/%d/edit', $post->id)));
    }

    public function getDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // check post before
        $post = $this->container['mappers.posts']->id($args['id']);

        if (!$post) {
            throw new \InvalidArgumentException('post not found');
        }

        $this->container['mappers.posts']->delete($post->id);
        $this->container['db']->delete($this->container['mappers.postmeta']->getTableName(), ['post' => $post->id]);

        $this->container['messages']->success(['Post deleted']);

        return $this->redirect($response, $this->container['url']->to('/admin/posts'));
    }

    protected function getValidator(array $input, Form $form): Validator
    {
        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');
        return $validator;
    }

    protected function getForm(array $attributes = []): Form
    {
        $form = new PostForm($attributes);
        $form->init();
        return $form;
    }
}
