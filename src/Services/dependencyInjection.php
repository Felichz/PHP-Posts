<?php namespace App\Services;

use App\Services\Container;
use Exception;

class DependencyInjection
{
    // Observacion: Se cumple principio Open/Closed de SOLID
    // No hay que modificar la clase para implementar nuevas dependencias

    static function obtenerInstancia( $class )
    {
        $container = new Container;

        $instance = $container->get( $class );
    
        return $instance;
    }
}

?>