<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\routes\routerMap;
use App\Model\BDUsers;

class DashboardController
{
    function index()
    {
        $twigVistas = new TwigVistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        $BDUsers = new BDUsers();

        $email = $_SESSION['user']['email'];

        $posts = $BDUsers->obtenerPosts( $email )->reverse();

        return new HtmlResponse( $twigVistas->renderizar('dashboard.twig.html', [
            'email' => $email,
            'posts' => $posts
            ]) );
    }
}

?>