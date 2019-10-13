<?php //Front Controller

// ======================== INICIALIZACIONES ========================

// Inicializar los errores
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
ERROR_REPORTING(E_ALL);

// Autoloader de Composer
require_once '../vendor/autoload.php';

// Cargar clases
use App\Controller\routerMap; // Clase con todas las rutas de la APP mapeadas
use Zend\Diactoros\Response\RedirectResponse; // Objeto para respuestas HTTP de redireccionamiento
use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

// Cargar archivo de configuracion
// Cargado desde carpeta raiz
require_once dirname(__DIR__) . '/config.php';

// Obtener configuraciones de rutas http para hacer redirecciones desde el cliente
$rutasPublicas = routerMap::obtenerRutasPublicas();

// Inicializar conexión a la BD
$eloquent = new App\Model\BDConection;
$eloquent -> conectar();

// Se inicia la sesion pero sin definirla
session_start();

// ======================== PROCESAR RUTAS ========================

/*
El router es un objeto al que le damos una ruta y este nos devuelve un Handler
dependiendo de que ruta le dimos, nosotros establecemos que handler nos debe
devolver con cada ruta, eso se especifica en el mapa de rutas, justamente se
mapea. El handler puede ser lo que nosotros queramos, un string, un arreglo, un
objeto, lo que queramos que nos devuelva.
*/

// Objeto HTTP Request - ZEND DIACTOROS
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$route = routerProcesarRequest($request); // Contiene los handlers

if (!$route)
{
    echo 'Error 404';
}
else if( permisosRuta() ){
    // Se obtiene la respuesta HTTP desde el controlador respectivo
    $response = ejecutarControlador($route);
}
else {
    $response = new redirectResponse($rutasPublicas['signin']);
}

// Procesar respuesta HTTP si es que la hay

if ( isset($response) ){
    aplicarHeaders($response);
    echo $response->getBody(); // Muestra el cuerpo de la respuesta tipo HtmlResponse
}

// ======================== FUNCIONES ========================

// Procesa la ruta obtenida y devuelve objeto con los handlers si es que los hay
function routerProcesarRequest($request) {

    $routerContainer = new \Aura\Router\RouterContainer();

    $map = $routerContainer->getMap(); // Molde de mapa de rutas
    $matcher = $routerContainer->getMatcher(); // Matcher, compara mapa con objeto request, devuelve handlers
    
    $map = routerMap::mapear($map);
    return $matcher->match($request);
}

// Devuelve si se tiene permitido acceder a la ruta o no
function permisosRuta() {
    GLOBAL $route;

    $necesitaAutenticacion = isset($route->handler['needsAuth']);
    $sesionDefinida = isset($_SESSION['user']);

    if(  $necesitaAutenticacion && !$sesionDefinida ) {
        $permisos = false;
    }
    else {
        $permisos = true;
    }

    return $permisos;
}

// Ejecuta controlador respectivo segun la ruta dada
function ejecutarControlador($route){

    $controllerClass = $route->handler['controllerClass'];
    $controllerAction = $route->handler['controllerAction'];

    $controllerObject = new $controllerClass;

    $response = $controllerObject -> $controllerAction();
    
    return $response;
}

// Aplica los headers de la respuesta HTTP
function aplicarHeaders($response) {

    foreach ($response->getHeaders() as $name => $values) {

        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
}

?>
