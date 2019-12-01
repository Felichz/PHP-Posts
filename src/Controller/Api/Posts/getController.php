<?php namespace App\Controller\Api\Posts;

use Zend\Diactoros\Response\EmptyResponse;

class getController
{
    public function init()
    {
        $response = new EmptyResponse;
        return $response;
    }
}