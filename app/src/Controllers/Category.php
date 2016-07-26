<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;
use Anchorcms\Filters;
use Anchorcms\Paginator;

class Category extends Frontend
{
    /**
     * View a category homepage listing the posts in that category.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return mixed String | ResponseInterface
     */
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // fetch category by slug
        $category = $this->container['mappers.categories']->slug($args['category']);

        if (false === $category) {
            return $this->notFound($request, $response, $args);
        }

        // create a page for our category
        $page = new PageModel([
            'title' => $category->title,
        ]);

        $pagenum = Filters::withDefault($request->getQueryParams(), 'page', FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 1,
                'min_range' => 1,
            ]
        ]);
        $perpage = $this->container['mappers.meta']->key('posts_per_page');

        $query = $this->container['mappers.posts']->query()
            ->andWhere('status = :status')
            ->setParameter('status', 'published')
            ->andWhere('category = :category')
            ->setParameter('category', $category->id);
        ;

        $total = $this->container['mappers.posts']->count($query);
        $pages = ceil($total / $perpage);
        $offset = ($pagenum - 1) * $perpage;

        $query->setMaxResults($perpage)
            ->setFirstResult($offset);

        $posts = $this->container['mappers.posts']->fetchAll($query);
        $this->container['services.posts']->hydrate($posts);

        // set globals
        $vars['page'] = $page;
        $vars['category'] = $category;
        $vars['paginator'] = new Paginator(sprintf('/category/%s', $category->slug), $pagenum, $pages);
        $vars['menu'] = $this->container['services.menu']->get();
        $vars['menu'] = $this->container['mappers.pages']->menu();
        $vars['categories'] = $this->container['services.categories']->allWithPostCounts();
        $vars['posts'] = $posts;

        $this->container['theme']->render($response, [
            sprintf('category-%s', $category->slug),
            'category',
            'posts',
            'index',
        ], $vars);

        return $response;
    }
}
