<?php namespace App\Routes;

use App\Interfaces\Router as RouterInterface;
use \Aura\Router\RouterContainer;

/*
    El router es un objeto al que le damos una ruta y este nos devuelve un Handler
    dependiendo de que ruta le dimos, nosotros establecemos que handler nos debe
    devolver con cada ruta, eso se especifica en el mapa de rutas, justamente se
    mapea. El handler puede ser lo que nosotros queramos, un string, un arreglo, un
    objeto, lo que queramos que nos devuelva.
*/

class Router implements RouterInterface
{
    static function routerContainer()
    {
        $routerContainer = new RouterContainer();

        $map = $routerContainer->getMap(); // Molde de mapa de rutas
        
        $map = routerMap::mapear( $map );

        return $routerContainer;
    }

    static function procesarRequest ( $request )
    {
        $routerContainer = self::routerContainer();
        
        $matcher = $routerContainer->getMatcher(); // Matcher, compara mapa con objeto request, devuelve handlers
        return $matcher->match( $request );
    }

    static function obtenerRutasHttp ()
    {
        return HttpRoutes::obtenerRutasHttp();
    }
}

?>