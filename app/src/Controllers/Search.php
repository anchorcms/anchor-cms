<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;
use Anchorcms\Filters;
use Anchorcms\Paginator;

class Search extends Frontend
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
            'q' => FILTER_SANITIZE_STRING
        ]);

        $page = new PageModel([
            'title' => sprintf('Search "%s"', $input['q']),
        ]);

        $query = $this->container['mappers.posts']->query()
            ->where('status = :status')
            ->setParameter('status', 'published')
            ->where('title LIKE :keywords')
            ->setParameter('keywords', '%'.$input['q'].'%');

        $total = $this->container['mappers.posts']->count($query);
        $perpage = $this->container['mappers.meta']->key('posts_per_page');
        $pages = ceil($total / $perpage);
        $offset = ($input['page'] - 1) * $perpage;

        $query->setMaxResults($perpage)
            ->setFirstResult($offset);

        $posts = $this->container['mappers.posts']->fetchAll($query);
        $this->container['services.posts']->hydrate($posts);

        // set globals
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['services.menu']->get();
        $vars['categories'] = $this->container['services.categories']->allWithPostCounts();
        $vars['paginator'] = new Paginator('/search', $input['page'], $pages, $perpage, $input);
        $vars['page'] = $page;
        $vars['keywords'] = $input['q'];
        $vars['posts'] = $posts;
        $vars['hasPosts'] = !empty($posts);

        $this->container['theme']->render($response, ['search', 'index'], $vars);

        return $response;
    }
}
