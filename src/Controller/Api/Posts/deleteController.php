<?php namespace App\Controller\Api\Posts;

use Zend\Diactoros\Response\EmptyResponse;

class deleteController
{
    public function init()
    {
        $response = new EmptyResponse;
        return $response;
    }
}