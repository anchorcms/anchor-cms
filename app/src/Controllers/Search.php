<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Anchorcms\Models\Page as PageModel;

class Search extends Frontend
{

    public function getIndex(ServerRequestInterface $request): string
    {
        $qs = $request->getUri()->getQuery();
        $keywords = filter_var($qs['q'] ?? '', FILTER_SANITIZE_STRING);

        // set globals
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['mappers.pages']->menu();
        $vars['categories'] = $this->container['mappers.categories']->all();

        $page = new PageModel([
            'title' => sprintf('Search "%s"', $keywords),
        ]);
        $vars['page'] = $page;
        $vars['keywords'] = $keywords;

        $query = $this->container['mappers.posts']->query();

        $query->where('status = :status')
            ->setParameter('status', 'published')
            ->where('title LIKE :keywords')
            ->setParameter('keywords', '%'.$keywords.'%');

        $posts = $this->container['mappers.posts']->fetchAll($query);
        $this->container['services.posts']->hydrate($posts);

        $vars['posts'] = $posts;
        $vars['hasPosts'] = ! empty($posts);

        return $this->container['theme']->render(['search', 'index'], $vars);
    }
}
