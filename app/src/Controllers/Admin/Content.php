<?php

namespace Anchorcms\Controllers\Admin;

use Anchorcms\Controllers\AbstractController;

class Content extends AbstractController
{
    public function postPreview($request)
    {
        $makrdown = filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW);
        $html = $this->container['markdown']->convertToHtml($makrdown);

        return $this->jsonResponse(['result' => true, 'html' => $html]);
    }
}
