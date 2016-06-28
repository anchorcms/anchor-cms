<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Anchorcms\Filters;

class Content extends AbstractController
{
    public function postPreview(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $makrdown = Filters::withDefault($request->getParsedBody(), 'content', FILTER_UNSAFE_RAW);
        $html = $this->container['markdown']->convertToHtml($makrdown);

		$response->getBody()->write(json_encode(['result' => true, 'html' => $html]));

        return $response->withStatus(200)->withHeader('content-type', 'application/json');
    }
}
