<?php namespace App\Controller\Container;

use Zend\Diactoros\ServerRequestFactory;

class Request
{
    public function __construct()
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }
}

?>