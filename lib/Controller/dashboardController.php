<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\routes\routerMap;
use App\Model\BDUsers;

class DashboardController
{
    function ejecutarDashboardController()
    {
        $twigVistas = new TwigVistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        
        $email = $_SESSION['user']['email'];

        $user = BDUsers::where('email', $email)->get()->first();
        $posts = $user->posts;

        return new HtmlResponse( $twigVistas->renderizar('dashboard.twig.html', [
            'email' => $email,
            'posts' => $posts
            ]) );
    }
}

?>