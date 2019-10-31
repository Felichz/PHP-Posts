<?php namespace App\Controller;

use App\Interfaces\Vistas;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\routes\routerMap;
use App\Model\BDUsers;

class DashboardController
{
    public function __construct( Vistas $vistas )
    {
        $this->vistas = $vistas;   
    }

    function index()
    {
        $vistas = $this->vistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        $BDUsers = new BDUsers();

        $email = $_SESSION['user']['email'];

        $posts = $BDUsers->obtenerPosts( $email )->reverse();

        return new HtmlResponse( $vistas->renderizar('dashboard.twig.html', [
            'email' => $email,
            'posts' => $posts
            ]) );
    }
}

?>