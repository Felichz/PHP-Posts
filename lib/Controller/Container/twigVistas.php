<?php namespace App\Controller\Container;

use App\Interfaces\Vistas;
use App\routes\routerMap;

// La funcion de esta clase es proveer un metodo para renderizar
// que contenga parametros que sean siempre default y comunes
// para cualquier render, como las rutas HTTP
class TwigVistas implements Vistas
{
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../lib/Vistas');
        $this->twigObject = new \Twig\Environment($loader);
    }

    public function renderizar(string $plantilla, array $parametrosUsuario = NULL)
    {
        GLOBAL $CONF;
        
        // Rutas para hacer REDIRECCIONES en las plantillas, se usan
        // desde el mapa de rutas para que sea sencillo cambiar
        // rutas, asi se cambia en las plantillas tambien
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        // App Address es la URL HTTP a la app
        // Se debe usar SOLO para solicitar archivos, para redireccionar
        // a rutas de la app se debe usar $rutasPublicas del routerMap
        if( !empty($CONF['APP_DIR']) ){
            $appAddress = $CONF['HOST'] . '/' . $CONF['APP_DIR'] . '/';
        }
        else {
            $appAddress = $CONF['HOST'] . '/';
        }

        // Verifica si hay una sesión iniciada
        $logged = isset($_SESSION['user']) ? true : false;
        
        $parametrosDefault = [
            'rutas' => $rutasPublicas,
            'appAddress' => $appAddress,
            'logged' => $logged
        ];

        $parametros = array_merge($parametrosUsuario, $parametrosDefault);

        $render = $this->twigObject->render($plantilla, $parametros);

        return $render;
    }
}

?>