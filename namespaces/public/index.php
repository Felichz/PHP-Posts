<?php //Front Controller

// Inicializar los errores
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
ERROR_REPORTING(E_ALL);

// Autoloader de Composer
require_once '../vendor/autoload.php';

// Inicializar conexión a la BD
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost:3306',
    'database'  => 'pruebaorm',
    'username'  => 'root',
    'password'  => '12345',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
// Sirve para mappear las rutas
$map = $routerContainer->getMap();
$map->get('index', '/PlatziPHP/', '../index.php');
$map->get('addPosts', '/PlatziPHP/posts/add', '../bdposts.php');
$map->post('addedPosts', '/PlatziPHP/posts/added', '../bdposts.php');
// El matcher compara el objeto request con lo que tenemos en el mapa de rutas
$matcher = $routerContainer->getMatcher();
// Le pasamos el objeto request
$route = $matcher->match($request);

if (!$route)
{
    echo 'No route';
}
else
{
    echo '<pre>';
    require $route->handler;
    echo '</pre>';
}

//echo '<br/>';
//var_dump($request->getUri()->getPath());

/*
$route = $_GET['route'] ?? '/';

if ($route == '/')
{
    require '../index.php';
}
else
{
    require '../' . $route . '.php';
}
*/

?>