<?php //Front Controller

// ======================== INICIALIZACIONES ========================

// Autoloader de Composer
require_once '../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

// Cargar archivo de configuracion
// Cargado desde carpeta raiz
$CONF = require dirname(__DIR__) . '/lib/config/config.php';

// Inicializar los errores
if ( $CONF['DEBUG'] == true ) {
    ERROR_REPORTING(E_ALL);
}
else
{
    ini_set('display_errors', false);
}
// Cargar clases
use App\routes\routerMap; // Clase con todas las rutas de la APP mapeadas
use Zend\Diactoros\Response\RedirectResponse; // Objeto para respuestas HTTP de redireccionamiento
use App\Controller\DependencyInjection; // Controla la inyeccion de dependencias
use App\Singletons\SingletonRequest;
use Zend\Diactoros\Response\HtmlResponse;

// Obtener configuraciones de rutas http para hacer redirecciones desde el cliente
$rutasPublicas = routerMap::obtenerRutasPublicas();

// Inicializar conexión a la BD
$eloquent = new App\Model\BDConection;
$eloquent -> conectar();

// Objeto HTTP Request - ZEND DIACTOROS
$request = SingletonRequest::getRequest();

// Se inicia la sesion pero sin definirla
session_start();

try {
    // ======================== PROCESAR RUTA ========================

    // Las Exception de codigo 1 siempre se muestran al usuario

    $route = routerProcesarRequest( $request ); // Devuelve los handlers

    if ( !$route )
    {
        throw new Exception('Error 404', 1);
    }
    
    $errorPermisos = verificarPermisosRuta( $route );

    if ( $errorPermisos == NULL )
    {
        // Se obtiene la respuesta HTTP desde el controlador respectivo
        $httpResponse = ejecutarControlador( $route );
    }
    else if ( $errorPermisos == 'needsAuth' ) {
        $httpResponse = new redirectResponse( $rutasPublicas['signin'] );
    }
    else if( $errorPermisos == 'needsNoSession' ){
        $httpResponse = new redirectResponse( $rutasPublicas['dashboard'] );
    }

    // ======================== PROCESAR RESPUESTA HTTP ========================

    if ( isset( $httpResponse ) ){

        if ( $httpResponse instanceof RedirectResponse )
        {
            aplicarHeaders( $httpResponse );
        }
        else if ( $httpResponse instanceof HtmlResponse )
        {
            echo $httpResponse->getBody(); // Muestra el cuerpo de respuesta HTTP, representa el HTML
        }
        else
        {
            throw new Exception('Invalid response', 1);
        }
    }
    else
    {
        throw new Exception('No response', 1);
    }
}
catch ( Exception $e ) {
    $controller = DependencyInjection::obtenerInstancia( 'App\Controller\ErrorMessageController' );
    $httpResponse = $controller->index( $e );

    echo $httpResponse->getBody();
}


// ======================== FUNCIONES ========================

/*
    El router es un objeto al que le damos una ruta y este nos devuelve un Handler
    dependiendo de que ruta le dimos, nosotros establecemos que handler nos debe
    devolver con cada ruta, eso se especifica en el mapa de rutas, justamente se
    mapea. El handler puede ser lo que nosotros queramos, un string, un arreglo, un
    objeto, lo que queramos que nos devuelva.
*/

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
function ejecutarControlador( $route ) {

    $controllerClass = $route->handler['controllerClass'];
    $controllerAction = $route->handler['controllerAction'];

    $controllerObject = DependencyInjection::obtenerInstancia( $controllerClass );

    // Por la interfaz de los controladores, sabemos que sus métodos
    // siempre retornan una respuesta HTTP
    $httpResponse = $controllerObject->$controllerAction();
    
    return $httpResponse;
}

// Aplica los headers de la respuesta HTTP
function aplicarHeaders($httpResponse) {

    foreach ($httpResponse->getHeaders() as $name => $values) {

        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
}

?>
