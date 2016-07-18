<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Media extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $files = $this->container['services.media']->get();

        return $this->json($response, [
            'result' => true,
            'files' => $files,
        ]);
    }

    public function postUpload(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $files = $request->getUploadedFiles();
            $name = $this->container['services.media']->upload($files['file']);

            $response = [
                'result' => true,
                'path' => sprintf('/content/%s', $name),
            ];
        } catch (\Throwable $e) {
            $response = [
                'result' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $this->json($response, $data);
    }
}
