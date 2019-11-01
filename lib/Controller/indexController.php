<?php namespace App\Controller;

use App\Interfaces\Vistas;

use Zend\Diactoros\Response\HtmlResponse;
use App\Model\BDPosts;
use App\Routes\Router;

class indexController
{
    public function __construct($HttpResponse, Vistas $vistas )
    {
        $this->HttpResponse = $HttpResponse;
        $this->vistas = $vistas;
    }

    public function index() 
    {
        GLOBAL $CONF;

        $HttpResponse = $this->HttpResponse;
        $vistas = $this->vistas;
        $rutasHttp = Router::obtenerRutasHttp();
        
        // Cargar autores de los post de la BD
        $posts = BDPosts::all()->reverse();

        // Obtener email de usuario
        $email = $_SESSION['user']['email'] ?? false;

        // Parrafos Lorem ipsum
        $ipsum = include $CONF['PATH']['UTILS'] . '/ipsum.php';

        // Renderizar la platilla index con Twig
        $response = $HttpResponse->HtmlResponse(
            $vistas->renderizar('index.twig.html', [
                'posts' => $posts,
                'ipsum' => $ipsum,
                'email' => $email
            ])
        );

        return $response;
    }
}

?>