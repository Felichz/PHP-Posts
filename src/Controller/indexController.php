<?php namespace App\Controller;

use App\Interfaces\Vistas;

use Zend\Diactoros\Response\HtmlResponse;
use App\Model\BDPosts;
use App\Routes\Router;

class indexController
{
    use \App\Traits\Miniatura;

    public function __construct($HttpResponse, Vistas $vistas, array $CONF)
    {
        $this->HttpResponse = $HttpResponse;
        $this->vistas = $vistas;
        $this->CONF = $CONF;
        $this->BDPosts = new BDPosts;
    }

    public function index() 
    {
        $HttpResponse = $this->HttpResponse;
        $vistas = $this->vistas;
        $CONF = $this->CONF;
        $BDPosts = $this->BDPosts;
        $rutasHttp = Router::obtenerRutasHttp();
        
        // Cargar autores de los post de la BD
        $posts = $BDPosts->cargarPosts();

        // Obtener email de usuario
        $email = $_SESSION['user']['email'] ?? false;

        // Parrafos Lorem ipsum
        $ipsum = include $CONF['PATH']['UTILS'] . '/ipsum.php';

        // Renderizar la platilla index con Twig
        $response = $HttpResponse->HtmlResponse(
            $vistas->renderizar('index.twig.html', [
                'posts' => $posts,
                'ipsum' => $ipsum,
                'email' => $email,
                'controller' => $this
            ])
        );

        return $response;
    }
}

?>