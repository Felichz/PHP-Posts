<?php namespace App\Controller\Container;

class Container
{
    public function get( $class )
    {
        $container = new \League\Container\Container;

        // Dependencias
        $container->add( 'Request', function(){
            GLOBAL $request;
            return $request;
        } );
        $container->add( 'Vistas', \App\Controller\Container\TwigVistas::class );
        $container->add( 'Validation', \App\Controller\Container\ValidationController::class );
        
        // Definir que dependencias inyectar como parametros en cada clase
        $container->add( \App\Controller\indexController::class) 
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