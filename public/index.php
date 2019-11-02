<?php //Front Controller

// ======================== INICIALIZACIONES ========================

// Autoloader de Composer
require_once '../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

// Cargar archivo de configuracion
// Cargado desde carpeta raiz
$CONF = require dirname(__DIR__) . '/src/config/config.php';

// Inicializar los errores
if ( $CONF['DEBUG'] == true ) {
    ERROR_REPORTING(E_ALL);
}
else
{
    ini_set('display_errors', false);
}
// Cargar clases
use App\Routes\Router;
use Zend\Diactoros\Response\RedirectResponse; // Objeto para respuestas HTTP de redireccionamiento
use App\Controller\DependencyInjection; // Controla la inyeccion de dependencias
use App\Singletons\SingletonRequest;
use Zend\Diactoros\Response\HtmlResponse;

use function App\Routes\procesarRequest;

// Obtener configuraciones de rutas http para hacer redirecciones desde el cliente
$rutasHttp = Router::obtenerRutasHttp();

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

    $HttpResponse = DependencyInjection::obtenerInstancia('HttpResponse');
    $route = Router::procesarRequest( $request ); // Devuelve los handlers

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
        $httpResponse = $HttpResponse->RedirectResponse( $rutasHttp['signin'] );
    }
    else if( $errorPermisos == 'needsNoSession' ){
        $httpResponse = $HttpResponse->RedirectResponse( $rutasHttp['dashboard'] );
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
