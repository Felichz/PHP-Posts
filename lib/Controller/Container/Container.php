<?php namespace App\Controller\Container;

use App\Singletons\SingletonRequest;

class Container
{
    public function get( $class )
    {
        $container = new \League\Container\Container;

        // Dependencias
        $container->add( 'Request', function(){
            return SingletonRequest::getRequest();
        });

        $container->add( 'Vistas', \App\Controller\Container\TwigVistas::class );

        $container->add( 'Validation', \App\Controller\Container\Validation::class );
        
        // Definir que dependencias inyectar como parametros en cada clase
        $container->add( \App\Controller\indexController::class) 
            ->addArgument( $container->get('Vistas') );

        $container->add( \App\Controller\ErrorMessageController::class) 
            ->addArgument( $container->get('Vistas') );

        $container->add( \App\Controller\DashboardController::class) 
            ->addArgument( $container->get('Vistas') );

        $container->add( \App\Controller\bdpostsController::class) 
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') );

        $container->add( \App\Controller\SigninController::class) 
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') );
        
        $container->add( \App\Controller\SignupController::class) 
            ->addArgument( $container->get('Request') )
            ->addArgument( $container->get('Vistas') )
            ->addArgument( $container->get('Validation') );
        
        return $container->get( $class );
    }
}

?>