<?php namespace App\Controller\Api\Posts;

use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;

class postController
{
    public function __construct( $request )
    {
        $this->request = $request;
    }

    public function init()
    {
        $response = new EmptyResponse();

        return $response;
    }
}