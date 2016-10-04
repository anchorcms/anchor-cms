<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;
use Anchorcms\Filters;
use Anchorcms\Paginator;

class Page extends Frontend
{
    /**
     * View a single generic page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     * @return ResponseInterface
     */
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $query = $this->container['mappers.pages']->query()
            ->andWhere('slug = :slug')
            ->setParameter('slug', $args['page'])
            ->andWhere('status = :status')
            ->setParameter('status', 'published')
        ;
        $page = $this->container['mappers.pages']->fetch($query);

        if (false === $page) {
            return $this->notFound($request, $response, $args);
        }

        // redirect homepage
        if ($page->id == $this->container['mappers.meta']->key('home_page')) {
            return $this->redirect($response, '/');
        }

        return $this->showPage($request, $response, $page);
    }

    /**
     * View the homepage which can be either the posts listing or a generic page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     * @return ResponseInterface
     */
    public function getHome(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // get the homepage ID
        $id = $this->container['mappers.meta']->key('home_page');
        // find the homepage
        $page = $this->container['mappers.pages']->id($id);

        // has the homepage been deleted? doh!
        if (false === $page) {
            return $this->notFound($request, $response, $args);
        }

        return $this->showPage($request, $response, $page);
    }

    /**
     * Render a page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param PageModel $page
     * @return ResponseInterface
     */
    protected function showPage(ServerRequestInterface $request, ResponseInterface $response, PageModel $page): ResponseInterface
    {
        // name of template files to check for
        $names = [];

        // set globals
        $vars['page'] = $page;
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['services.menu']->get();
        $vars['categories'] = $this->container['services.categories']->allWithPostCounts();

        // is this page the post listings page?
        if ($page->id == $this->container['mappers.meta']->key('posts_page')) {
            $pagenum = Filters::withDefault($request->getQueryParams(), 'page', FILTER_VALIDATE_INT, [
                'options' => [
                    'default' => 1,
                    'min_range' => 1,
                ],
            ]);
            $perpage = $this->container['mappers.meta']->key('posts_per_page');

            $query = $this->container['mappers.posts']->query()
                ->andWhere('status = :status')
                ->setParameter('status', 'published')
            ;

            $total = $this->container['mappers.posts']->count($query);
            $pages = ceil($total / $perpage);
            $offset = ($pagenum - 1) * $perpage;

            $query->setMaxResults($perpage)
                ->setFirstResult($offset);

            $posts = $this->container['mappers.posts']->fetchAll($query);
            $this->container['services.posts']->hydrate($posts);

            $vars['posts'] = $posts;
            $vars['paginator'] = new Paginator('/', $pagenum, $pages);

            $names[] = 'posts';
        } else {
            $names[] = sprintf('page-%s', $page->slug);
            $names[] = 'page';
        }

        $names[] = 'index';

        $this->container['theme']->render($response, $names, $vars);

        return $response;
    }
}
