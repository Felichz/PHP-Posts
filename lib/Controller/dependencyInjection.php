<?php   declare(strict_types=1);
        namespace App\Controller;

use App\Controller\Container\Container;

class DependencyInjection
{
    static function obtenerInstancia( $class )
    {
        $container = new Container;

        $instance = $container->get( $class );

        return $instance;
    }
}

?>