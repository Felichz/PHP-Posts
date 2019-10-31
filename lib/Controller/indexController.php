<?php namespace App\Controller;

use App\Interfaces\Vistas;

use Zend\Diactoros\Response\HtmlResponse;
use App\routes\routerMap;
use App\Model\BDPosts;

class indexController
{
    public function __construct( Vistas $vistas )
    {
        $this->vistas = $vistas;
    }

    public function index() 
    {
        GLOBAL $CONF;

        $vistas = $this->vistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        
        // Cargar autores de los post de la BD
        $posts = BDPosts::all()->reverse();

        // Obtener email de usuario
        $email = $_SESSION['user']['email'] ?? false;

        // Parrafos Lorem ipsum
        $ipsum = include $CONF['PATH']['UTILS'] . '/ipsum.php';

        // Renderizar la platilla index con Twig
        $response = new HtmlResponse(
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