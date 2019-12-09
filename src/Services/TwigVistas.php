<?php namespace App\Services;

use App\Interfaces\Vistas;
use App\Routes\Router;

// La funcion de esta clase es proveer un metodo para renderizar
// que contenga parametros que sean siempre default y comunes
// para cualquier render, como las rutas HTTP
class TwigVistas implements Vistas
{
    public function __construct( array $CONF )
    {
        $this->CONF = $CONF;

        $loader = new \Twig\Loader\FilesystemLoader( $CONF['PATH']['ROOT'] . '/src/Vistas');
        $this->twigObject = new \Twig\Environment($loader);
    }

    public function renderizar(string $plantilla, array $parametrosUsuario = NULL)
    {
        $CONF = $this->CONF;
        
        // Rutas para hacer REDIRECCIONES en las plantillas, se usan
        // desde el mapa de rutas para que sea sencillo cambiar
        // rutas, asi se cambia en las plantillas tambien
        $rutasHttp = Router::obtenerRutasHttp();

        // App Address es la URL HTTP a la app
        // Se debe usar SOLO para solicitar archivos, para redireccionar
        // a rutas de la app se debe usar $rutasHttp del Router
        if( !empty($CONF['APP_URI']) ){
            $appAddress = $CONF['HOST'] . '/' . $CONF['APP_URI'] . '/';
        }
        else {
            $appAddress = $CONF['HOST'] . '/';
        }

        // Verifica si hay una sesión iniciada
        $logged = isset($_SESSION['user']) ? true : false;
        
        $parametrosDefault = [
            'rutas' => $rutasHttp,
            'appAddress' => $appAddress,
            'logged' => $logged
        ];

        if ( $parametrosUsuario )
        {
            $parametros = array_merge($parametrosUsuario, $parametrosDefault);
        }
        else
        {
            $parametros = $parametrosDefault;
        }

        $render = $this->twigObject->render($plantilla, $parametros);

        return $render;
    }
}

?>