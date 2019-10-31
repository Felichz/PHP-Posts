<?php //Front Controller

// ======================== INICIALIZACIONES ========================

// Inicializar los errores
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
ERROR_REPORTING(E_ALL);

// Autoloader de Composer
require_once '../vendor/autoload.php';

// Cargar clases
use App\routes\routerMap; // Clase con todas las rutas de la APP mapeadas
use Zend\Diactoros\Response\RedirectResponse; // Objeto para respuestas HTTP de redireccionamiento
use Dotenv\Dotenv;  // Variables de entorno
use App\Controller\DependencyInjection; // Controla la inyeccion de dependencias
use App\Singletons\SingletonRequest;

// Cargar variables de entorno
$dotenv = Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

// Cargar archivo de configuracion
// Cargado desde carpeta raiz
$CONF = require dirname(__DIR__) . '/lib/Config/config.php';

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
$request = SingletonRequest::getRequest();

$route = routerProcesarRequest( $request ); // Contiene los handlers

if ( !$route )
{
    echo 'Error 404';
}
else
{
    $errorPermisos = verificarPermisosRuta( $route );
}

if ( $errorPermisos == 'needsAuth' ) {
    $response = new redirectResponse( $rutasPublicas['signin'] );
}
else if( $errorPermisos == 'needsNoSession' ){
    $response = new redirectResponse( $rutasPublicas['dashboard'] );
}
else {
    // Se obtiene la respuesta HTTP desde el controlador respectivo
    $response = ejecutarControlador($route);
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
function verificarPermisosRuta( $route ) {

    $sesionDefinida = isset( $_SESSION['user'] );
    $necesitaAutenticacion = isset( $route->handler['needsAuth'] );
    $needsNoSession = isset( $route->handler['needsNoSession'] );

    $errorPermisos = NULL;

    if ( $needsNoSession && $sesionDefinida)
    {
        $errorPermisos = 'needsNoSession';
    }
    else if (  $necesitaAutenticacion && !$sesionDefinida ) {
        $errorPermisos = 'needsAuth';
    }

    return $errorPermisos;
}

// Ejecuta controlador respectivo segun la ruta dada
function ejecutarControlador($route) {

    $controllerClass = $route->handler['controllerClass'];
    $controllerAction = $route->handler['controllerAction'];

    $controllerObject = DependencyInjection::obtenerInstancia( $controllerClass );

    // Por la interfaz de los controladores, sabemos que sus métodos
    // siempre retornan una respuesta HTTP
    return $controllerObject->$controllerAction();
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
