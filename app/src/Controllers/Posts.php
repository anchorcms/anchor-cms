<?php

namespace Anchorcms\Controllers;

use Anchorcms\Events\FilterEvent;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;

class Posts extends Frontend
{
    /**
     * view a single post.
     *
     * @access public
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // get post by slug
        $query = $this->container['mappers.posts']->query()
            ->andWhere('slug = :slug')
            ->setParameter('slug', $args['post'])
            ->andWhere('status = :status')
            ->setParameter('status', 'published')
        ;

        $article = $this->container['mappers.posts']->fetch($query);

        // post not found
        if (false === $article) {
            return $this->notFound($request, $response, $args);
        }

        // hydrate post
        $this->container['services.posts']->hydrate([$article]);

        // redirect to the correct category slug
        if ($article->getCategory()->slug != $args['category']) {
            return $this->redirect($response, $article->url());
        }

        // create mock page for post
        $page = new PageModel([
            'title' => $article->title,
        ]);

        $vars['page'] = $page;

        $filters = new FilterEvent($request);
        $this->container['events']->dispatch('filters', $filters);
        $vars['post'] = $filters->applyFilters($article);

        // set globals
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['services.menu']->get();
        $vars['categories'] = $this->container['services.categories']->allWithPostCounts();

        $this->container['theme']->render($response, [
            sprintf('article-%s', $article->slug),
            sprintf('article-%s', $article->getCategory()->slug),
            'article',
        ], $vars);

        return $response;
    }
}
