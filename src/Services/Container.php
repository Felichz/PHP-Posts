<?php namespace App\Services;

use App\Conf\Conf;
use App\Singletons\SingletonRequest;

class Container
{
    public function get( $class )
    {
        $container = new \League\Container\Container;

        // Dependencias
        // Dependencias
        $container->add( 'Conf', function(){
            return Conf::getConf();
        });

        $container->add( 'Request', function(){
            return SingletonRequest::getRequest();
        });

        $container->add( 'Vistas', function () {
            return new TwigVistas( Conf::getConf() );
        });

        $container->add( 'Validation', \App\Services\Validation::class );

        $container->add( 'HttpResponse', \App\Services\HttpResponse::class )
            ->addArgument( \Zend\Diactoros\Response\HtmlResponse::class )
            ->addArgument( \Zend\Diactoros\Response\RedirectResponse::class );
        
        // Definir que dependencias inyectar como parametros en cada clase

        // CONTROLLERS
        $container->add( \App\Controller\indexController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Conf') );

        $container->add( \App\Controller\ErrorMessageController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Conf') );

        $container->add( \App\Controller\DashboardController::class )
            ->addArgument( $container->get('HttpResponse') )
            ->addArgument( $container->get('Vistas') );

        $container->add( \App\Controller\bdpostsController::class )
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

        return $container->get( $class );
    }
}

?>