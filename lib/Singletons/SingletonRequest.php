<?php namespace App\Singletons;

use Zend\Diactoros\ServerRequestFactory;

class SingletonRequest
{
    private static $request = NULL;

    static function getRequest()
    { 
        if ( self::$request === NULL )
        {
            self::$request = ServerRequestFactory::fromGlobals(
                $_SERVER,
                $_GET,
                $_POST,
                $_COOKIE,
                $_FILES
            );
        }

        return self::$request;
    }
}

?>