<?php namespace App\Controller;

use App\Interfaces\Vistas;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\Model\BDUsers;
use App\Routes\Router;

class DashboardController
{
    public function __construct($HttpResponse, Vistas $vistas )
    {
        $this->HttpResponse = $HttpResponse;
        $this->vistas = $vistas;   
    }

    function index()
    {
        $HttpResponse = $this->HttpResponse;
        $vistas = $this->vistas;
        $rutasHttp = Router::obtenerRutasHttp();
        $BDUsers = new BDUsers();

        $email = $_SESSION['user']['email'];

        $posts = $BDUsers->obtenerPosts( $email );

        return $HttpResponse->HtmlResponse(
            $vistas->renderizar('dashboard.twig.html', [
                'email' => $email,
                'posts' => $posts
            ]) 
        );
    }
}

?>