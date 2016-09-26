<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\TransferException;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Paginator;
use Anchorcms\Filters;

class Plugins extends AbstractController
{
    /**
     * handle plugin list requests
     *
     * @access public
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
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

        $total = $this->container['services.plugins']->countPlugins();
        $perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
        $offset = ($input['page'] - 1) * $perpage;

        $plugins = $this->container['services.plugins']->getPlugins();

        $paging = Paginator::create($this->container['url']->to('/admin/plugins'), $input['page'], ceil($total / $perpage));

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Plugins';

        $vars['plugins'] = $plugins;

        $vars['paging'] = $paging;

        $this->renderTemplate($response, 'layouts/default', 'plugins/index', $vars);

        return $response;
    }

    /**
     * handle plugin details and settings requests
     *
     * @access public
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function getSettings(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $vars = [];

        $plugin = $this->container['services.plugins']->getPlugin(urldecode($args['name']));

        $vars['plugin'] = $plugin;

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Plugin ' . $plugin->getName();

        $this->renderTemplate($response, 'layouts/default', 'plugins/settings', $vars);

        return $response;
    }

    /**
     * redirect failed requests to the plugin page
     *
     * @access public
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function getDisable(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->redirect($response, $this->container['url']->to('/admin/plugins'));
    }

    /**
     * redirect failed requests to the plugin page
     *
     * @access public
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function getEnable(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->redirect($response, $this->container['url']->to('/admin/plugins'));
    }

    /**
     * handle plugin disable requests
     *
     * @access public
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function postDisable(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $plugin = $this->container['services.plugins']->getPlugin(urldecode($args['name']));

        $plugin->setInactive();

        $this->container['messages']->success(['Plugin disabled']);
        return $this->redirect($response, $this->container['url']
            ->to(sprintf('/admin/plugins/%s', urlencode(strtolower($plugin->getName()))))
        );
    }

    /**
     * handle plugin enable requests
     *
     * @access public
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function postEnable(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $plugin = $this->container['services.plugins']->getPlugin(urldecode($args['name']));

        $plugin->setActive();

        $this->container['messages']->success(['Plugin enabled']);
        return $this->redirect($response, $this->container['url']
            ->to(sprintf('/admin/plugins/%s', urlencode(strtolower($plugin->getName()))))
        );
    }

    public function getVersionCheck(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // resolve plugin name to prevent XSS attacks
        $plugin = $this->container['services.plugins']->getPlugin(urldecode($args['name']));

        try {
            $repositoryResponse = (new HttpClient())->request(
                'GET',

                // TODO: real anchor plugin repo URL here, eg. https://plugins.anchorcms.com/repository
                sprintf('http://gateway.9dev.de/repository/%s', urlencode(strtolower($plugin->getName()))),
                [
                    'query' => ['installedVersion' => $plugin->getVersion()],
                    'timeout' => 5,
                    'headers' => [
                        'User-Agent' => 'anchor-cms/1.0'
                    ]
                ]
            );
        } catch (TransferException $transferException) {
            switch ($transferException->getCode()) {
                case 0:
                    return $this->json($response, [
                        'status' => 'error',
                        'message' => 'could not connect to upstream repository server.'
                    ]);
                    break;

                case 404:
                    return $this->json($response, [
                        'status' => 'error',
                        'message' => 'the plugin could not be found in the repository.'
                    ]);
                    break;

                default:
                    return $this->json($response, [
                        'status' => 'error',
                        'message' => $transferException->getMessage()
                    ]);
            }
        }

        $installedVersion = $plugin->getVersion();
        $currentVersion = (string)trim($repositoryResponse->getBody());

        return $this->json($response, [
            'status' => ($installedVersion === $currentVersion ? 'up to date' : 'update required'),
            'installedVersion' => $installedVersion,
            'currentVersion' => $currentVersion
        ]);
    }
}
