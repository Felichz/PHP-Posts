<?php //Front Controller

// ======================== INICIALIZACIONES ========================

//// Autoloader de Composer ////

require_once '../vendor/autoload.php';

//// Cargar variables de entorno ////
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..'); // Debe apuntar a la carpeta raiz
$dotenv->load();

//// Cargar configuracion ////
$CONF = App\Conf\Conf::getConf();

//// Inicializar los errores ////
if ( $CONF['DEBUG'] == true ) {
    ini_set('display_errors', true);
    ini_set('display_startup_error', true);
    ERROR_REPORTING(E_ALL);
}
else
{
    ini_set('display_errors', false);
    ini_set('display_startup_error', false);
    ERROR_REPORTING(0);
}

//// Cargar clases ////

// App //
use App\Routes\Router;
use App\Services\Container;
use App\Services\DependencyInjection; // Controla la inyeccion de dependencias
use App\Singletons\SingletonRequest; // Devuelve objeto request PSR-7 de Zend Diactoros

// PSR-7 Response
use Zend\Diactoros\Response;

// Logger //
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Harmony Request Handler //
use WoohooLabs\Harmony\Harmony;
// Middlewares //
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use Middlewares\AuraRouter;
use App\Middlewares\AppWhoopsMiddleware;
use App\Middlewares\AuthMiddleware;

// Inicializar Logger, create a log channel
$log = new Logger('app');
$log->pushHandler(new StreamHandler($CONF['PATH']['LOG'] . '/app.log', Logger::WARNING));

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
            $harmony->addMiddleware( new AppWhoopsMiddleware($log) ); // Debug lib
        }

        $harmony->addMiddleware(new AuraRouter($router))
                ->addMiddleware(new AuthMiddleware($rutasHttp, 'request-handler'))
                ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
                ->run();
} 
catch ( Exception $e ) {
    $log->warning( $e->getMessage() );

    errorMessageController( $e );
}
catch ( Error $e ) {
    $log->error( $e->getMessage() );

    errorMessageController( $e );
}

function errorMessageController( $e ) {
    $controller = DependencyInjection::obtenerElemento( 'App\Controller\ErrorMessageController' );
    $httpResponse = $controller->index( $e->getMessage() );

    $emitter->emit($httpResponse);
}

?>