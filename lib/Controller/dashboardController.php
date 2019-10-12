<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\Controller\routerMap;

class DashboardController
{
    function ejecutarDashboardController()
    {
        $twigVistas = new TwigVistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        
        $email = $_SESSION['user']['email'];
        return new HtmlResponse( $twigVistas->renderizar('dashboard.twig.html', [
            'email' => $email
            ]) );
    }
}

?>