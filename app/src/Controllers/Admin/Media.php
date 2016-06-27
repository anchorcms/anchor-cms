<?php

namespace Anchorcms\Controllers\Admin;

use Anchorcms\Controllers\AbstractController;

class Media extends AbstractController
{
    public function getIndex()
    {
        if ($since = filter_input(INPUT_GET, 'since', FILTER_SANITIZE_STRING)) {
            $files = $this->container['services.media']->get(function (\SplFileInfo $file) use ($since) {
                return $file->getMTime() > $since;
            });
        } else {
            $files = $this->container['services.media']->get();
        }

        return $this->jsonResponse(['result' => true, 'files' => $files]);
    }

    public function postUpload($request)
    {
        try {
            $files = $request->getUploadedFiles();
            $url = '/content'.$this->container['services.media']->upload($files['file']);
            $response = ['result' => true, 'path' => $url];
        } catch (\Exception $e) {
            $response = ['result' => false, 'message' => $e->getMessage()];
        }

        return $this->jsonResponse($response);
    }
}
