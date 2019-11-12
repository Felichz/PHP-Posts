<?php //Front Controller

// ======================== INICIALIZACIONES ========================

// Autoloader de Composer

require_once '../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

// Cargar configuracion
$CONF = App\Conf\Conf::getConf();

// Inicializar los errores
if ( $CONF['DEBUG'] == true ) {
    ERROR_REPORTING(E_ALL);
}
else
{
    ini_set('display_errors', false);
}
// Cargar clases

use App\Middlewares\AuthMiddleware;
use App\Routes\Router;
use App\Services\Container;
use App\Services\DependencyInjection; // Controla la inyeccion de dependencias
use App\Singletons\SingletonRequest;

use Middlewares\AuraRouter;

use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;

use Franzl\Middleware\Whoops\WhoopsMiddleware;

// Obtener configuraciones de rutas http para hacer redirecciones desde el cliente
$rutasHttp = Router::obtenerRutasHttp();

// Inicializar conexión a la BD
$eloquent = new App\Model\BDConection;
$eloquent -> conectar();

// Objeto HTTP Request - ZEND DIACTOROS
$request = SingletonRequest::getRequest();

// Se inicia la sesion pero sin definirla
session_start();

// ======================== REQUEST HANDLER ARMONY ========================

$router = Router::routerContainer();
$container = Container::getContainer();

$harmony = new Harmony($request, new Response());

$emitter = new SapiEmitter;

try {
        $harmony->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()));
        
        if( $CONF['DEBUG'] === true ) {
            $harmony->addMiddleware(new WhoopsMiddleware); // Debug lib
        }

        $harmony
        ->addMiddleware(new AuraRouter($router))
        ->addMiddleware(new AuthMiddleware($rutasHttp, 'request-handler'))
        ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
        ->run();
} 
catch ( Exception $e ) {
    $controller = DependencyInjection::obtenerElemento( 'App\Controller\ErrorMessageController' );
    $httpResponse = $controller->index( $e->getMessage() );

    $emitter->emit($httpResponse);
}
catch ( Error $e ) {
    $controller = DependencyInjection::obtenerElemento( 'App\Controller\ErrorMessageController' );
    $httpResponse = $controller->index( $e->getMessage() );

    $emitter->emit($httpResponse);
}

?>