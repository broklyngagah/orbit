<?php

use Phalcon\Mvc\View;

trait ResponseApiTrait
{
    protected function sendResponse(array $data, $code = 200, $headers = [])
    {
        View::LEVEL_NO_RENDER;

        return $this->response->send();
    }
}
