<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;
use Anchorcms\Filters;

class Page extends Frontend
{
    /**
     * View a single generic page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $page = $this->container['mappers.pages']->slug($args['page']);

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
     *
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
     * View a category homepage listing the posts in that category.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return mixed String | ResponseInterface
     */
    public function getCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
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

        // set globals
        $vars['page'] = $page;
        $vars['category'] = $category;
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['mappers.pages']->menu();
        $vars['categories'] = $this->container['mappers.categories']->all();

        $pagenum = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT, ['options' => ['default' => 1]]);
        $perpage = $this->container['mappers.meta']->key('posts_per_page');

        $posts = $this->container['services.posts']->getPosts([
            'page' => $pagenum,
            'perpage' => $perpage,
            'category' => $category->id,
        ]);
        $vars['posts'] = $posts;

        $this->container['theme']->render($response, ['category', 'posts', 'index'], $vars);

        return $response;
    }

    /**
     * Render a page.
     *
     * @param object PageModel
     *
     * @return string Template output
     */
    protected function showPage(ServerRequestInterface $request, ResponseInterface $response, PageModel $page)
    {
        // name of template files to check for
        $names = [];

        // set globals
        $vars['page'] = $page;
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['mappers.pages']->menu();
        $vars['categories'] = $this->container['mappers.categories']->all();

        // is this page the post listings page?
        if ($page->id == $this->container['mappers.meta']->key('posts_page')) {
            $pagenum = Filters::withDefault($request->getQueryParams(), 'page', FILTER_VALIDATE_INT, [
                'options' => [
                    'default' => 1,
                    'min_range' => 1,
                ],
            ]);
            $perpage = $this->container['mappers.meta']->key('posts_per_page');

            $vars['posts'] = $this->container['services.posts']->getPosts([
                'page' => $pagenum, // make a positive int
                'perpage' => $perpage,
            ]);

            $names[] = 'posts';
        } else {
            $names[] = 'page';
        }

        $names[] = 'index';

        $this->container['theme']->render($response, $names, $vars);

        return $response;
    }
}
