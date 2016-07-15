<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Media extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if ($since = filter_input(INPUT_GET, 'since', FILTER_SANITIZE_STRING)) {
            $files = $this->container['services.media']->get(function (\SplFileInfo $file) use ($since) {
                return $file->getMTime() > $since;
            });
        } else {
            $files = $this->container['services.media']->get();
        }

        return $this->json($response, [
            'result' => true,
            'files' => $files,
        ]);
    }

    public function postUpload(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $files = $request->getUploadedFiles();
            $url = '/content'.$this->container['services.media']->upload($files['file']);
            $data = ['result' => true, 'path' => $url];
        } catch (\Exception $e) {
            $data = ['result' => false, 'message' => $e->getMessage()];
        }

        return $this->json($response, $data);
    }
}
