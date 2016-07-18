<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Media extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $files = $this->container['services.media']->listContents();

        return $this->json($response, [
            'result' => true,
            'path' => $this->container['config']->get('uploads.url'),
            'files' => $files,
        ]);
    }

    public function postUpload(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $files = $request->getUploadedFiles();
            $name = $this->container['services.media']->upload($files['file']);

            $data = [
                'result' => true,
                'path' => sprintf('%s/%s', $this->container['config']->get('uploads.url'), $name),
            ];
        } catch (\Throwable $e) {
            $data = [
                'result' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $this->json($response, $data);
    }
}
