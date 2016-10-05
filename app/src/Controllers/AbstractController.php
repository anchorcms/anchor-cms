<?php

namespace Anchorcms\Controllers;

use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Events\Admin\BeforeRenderEvent;
use Anchorcms\Events\Admin\BuildScriptsEvent;
use Anchorcms\Events\Admin\BuildStylesEvent;

abstract class AbstractController implements ControllerInterface
{
    protected $container;

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    protected function renderTemplate(ResponseInterface $response, string $layout, string $template, array $vars = [])
    {
        $vars['messages'] = $this->container['view']->render('partials/messages', [
            'messages' => $this->container['messages']->get(),
        ]);

        $beforeRender = new BeforeRenderEvent($template, $vars);
        $this->container['events']->dispatch('admin:beforeRender', $beforeRender);
        $vars['body'] = $this->container['view']->render($beforeRender->getTemplate(), $beforeRender->getVars());

        $buildScripts = new BuildScriptsEvent();
        $this->container['events']->dispatch('admin:buildScripts', $buildScripts);

        $buildStyles = new BuildStylesEvent();
        $this->container['events']->dispatch('admin:buildStyles', $buildStyles);

        $vars['scripts'] = $buildScripts->getScripts();
        $vars['styles'] = $buildStyles->getStyles();

        $vars['additionalMenuItems'] = [];

        $beforeLayoutRender = new BeforeRenderEvent($layout, $vars);
        $this->container['events']->dispatch('admin:beforeLayoutRender', $beforeLayoutRender);
        $layout = $this->container['view']->render($beforeLayoutRender->getTemplate(), $beforeLayoutRender->getVars());
        $response->getBody()->write($layout);
    }

    /**
     * redirects the client
     *
     * @access protected
     *
     * @param ResponseInterface $response
     * @param string            $path
     * @param int               $status
     *
     * @return ResponseInterface
     */
    protected function redirect(ResponseInterface $response, string $path, int $status = 302): ResponseInterface
    {
        return $response->withStatus($status)->withHeader('Location', $path);
    }

    /**
     * creates a JSON response
     *
     * @access protected
     *
     * @param ResponseInterface $response
     * @param array             $data
     *
     * @return ResponseInterface
     */
    protected function json(ResponseInterface $response, array $data): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));

        // detect invalid JSON
        if (json_last_error()) throw new \RuntimeException('Invalid JSON: ' . json_last_error_msg());

        return $response->withHeader('Content-Type', 'application/json');
    }
}
