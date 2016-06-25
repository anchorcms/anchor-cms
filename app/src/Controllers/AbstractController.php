<?php

namespace Anchorcms\Controllers;

use Pimple\Container;
use Psr\Http\Message\ResponseInterface;

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

        $vars['body'] = $this->container['view']->render($template, $vars);

        $layout = $this->container['view']->render($layout, $vars);
        $response->getBody()->write($layout);
    }

    protected function redirect(ResponseInterface $response, string $path, int $status = 302): ResponseInterface
    {
        return $response->withStatus($status)->withHeader('Location', $path);
    }
}
