<?php namespace App\Services;

use App\Conf\Conf;
use App\Singletons\SingletonRequest;

// Singleton Dependency Injection Container

class Container
{
    static private $container = NULL;

    static private function newContainer()
    {
        $container = new \League\Container\Container;

        $CONF = Conf::getConf();

        // Dependencias
        $container->add( 'Conf', function() use ($CONF) {
            return $CONF;
        });

        $container->add( 'Request', function(){
            return SingletonRequest::getRequest();
        });

        $container->add( 'Vistas', function () use ($CONF) {
            return new TwigVistas( $CONF );
        });

        $container->add( 'Validation', \App\Services\Validation::class );

        $container->add( 'HttpResponse', \App\Services\HttpResponse::class )
            ->addArgument( \Zend\Diactoros\Response\HtmlResponse::class )
            ->addArgument( \Zend\Diactoros\Response\RedirectResponse::class );
        
        $container->add( 'Mailer', function () use ($CONF) {
                return new \App\Services\SwiftMailer($CONF['EMAIL']['SMTP']);
            });

        $container->add( 'AsyncCommand', function () use ($CONF) {
            return new \App\Services\AsyncCommand( $CONF );
        });

        // Definir que dependencias inyectar como parametros en cada clase
        // CONTROLLERS

        // Api
        $container->add( \App\Controller\Api\Posts\getController::class );
        $container->add( \App\Controller\Api\Posts\postController::class )
            ->addArgument( $container->get('Request') );
        $container->add( \App\Controller\Api\Posts\putController::class );
        $container->add( \App\Controller\Api\Posts\deleteController::class );

        // App
        $container->add( \App\Controller\IndexController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Conf') );

        $container->add( \App\Controller\ErrorMessageController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Conf') );

        $container->add( \App\Controller\ContactController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') )
            ->addArgument( $container->get('Conf') )
            ->addArgument( $container->get('Mailer') )
            ->addArgument( $container->get('AsyncCommand') );

        $container->add( \App\Controller\BdpostsController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') )
            ->addArgument( $container->get('Conf') );

        $container->add( \App\Controller\SigninController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') );
        
        $container->add( \App\Controller\SignupController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') );

        $container->add( \App\Controller\DashboardController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Vistas') );

            return $container;
    }

    static function getContainer()
    {
        if ( self::$container === NULL )
        {
            self::$container = self::newContainer();
        }

        return self::$container;
    }
}

?>