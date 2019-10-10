<?php //Front Controller

// Inicializar los errores
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
ERROR_REPORTING(E_ALL);

// Se inicia la sesion pero sin definirla
session_start();

// Autoloader de Composer
require_once '../vendor/autoload.php';

// Cargar variables de entorno (configuracion local)
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

// Inicializar conexión a la BD
$eloquent = new App\Model\BDConection;
$eloquent -> conectar();

// Plantillas Twig
$loader = new \Twig\Loader\FilesystemLoader('../lib/Vistas');
$twig = new \Twig\Environment($loader);

// Objeto HTTP Request - ZEND DIACTOROS
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

use Zend\Diactoros\Response\RedirectResponse; // Objeto para redirecciones

/*
El router es un objeto al que le damos una ruta y este nos devuelve un Handler
dependiendo de que ruta le dimos, nosotros establecemos que handler nos debe
devolver con cada ruta, eso se especifica en el mapa de rutas, justamente se
mapea. El handler puede ser lo que nosotros queramos, un string, un arreglo, un
objeto, lo que queramos que nos devuelva.
*/

// Clase con todas las rutas de nuestra app mapeadas
use App\Controller\routerMap;

$routerContainer = new \Aura\Router\RouterContainer();
// Mapa de rutas
$map = $routerContainer->getMap();
$map = routerMap::mapear($map);
// El matcher compara el objeto request con lo que tenemos en el mapa de rutas
// y devuelve el respectivo handler con todos los datos
$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route)
{
    echo 'No route';
}
else
{
    // Verifica si hay permisos para acceder a la ruta antes de ejecutarla
    if( isset($route->handler['needsAuth']) ) {
        if( isset($_SESSION['user']) ) {
            //$execRoute = true;
            // Nothing
        }
        else {
            $response = new RedirectResponse('/PlatziPHP/user/signin');
        }
    }
    else {
        //$execRoute = true;
        // Nothing
    }

    // Se obtiene la respuesta HTTP desde el controlador
    // Sistema para ejecutar el controlador respectivo según la ruta en la que estemos
    if( !isset($response) ) {
        $controllerName = $route->handler['controllerName'];
        $controllerAction = $route->handler['controllerAction'];
    
        $controllerObject = new $controllerName;
    
        // Verifica si hay que pasar parametros al controlador
        // Y luego obtiene la respuesta HTTP
        if(array_key_exists('controllerParameters', $route->handler))
        {
            // $controllerParameters debe estar en el formato '$param1, $param2, $param3, ...'
            $controllerParameters = $route->handler['controllerParameters'];
    
            $code = sprintf('$response = $controllerObject -> $controllerAction(%s);', $controllerParameters);
            eval($code); // eval() ejecuta un string como código PHP
        }
        else
        {
            $response = $controllerObject -> $controllerAction();
        }
    }

    // Procesar respuesta HTTP
    if($response)
    {
        // Establecer Headers
        foreach ($response->getHeaders() as $name => $values) {

            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        // Mostrar el cuerpo de la respuesta HTTP, es un HTML HtmlResponse
        echo $response->getBody();
    }
}

?>