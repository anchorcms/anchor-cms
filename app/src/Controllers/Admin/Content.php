<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Content extends AbstractController
{
    public function postPreview(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $makrdown = filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW);
        $html = $this->container['markdown']->convertToHtml($makrdown);

        return $this->jsonResponse(['result' => true, 'html' => $html]);
    }
}
