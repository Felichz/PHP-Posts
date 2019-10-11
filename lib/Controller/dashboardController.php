<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;

class DashboardController
{
    function ejecutarDashboardController()
    {
        $twig = TwigVistas:: obtenerTwig();
        
        $email = $_SESSION['user']['email'];
        return new HtmlResponse( $twig->render('dashboard.twig.html', [
            'email' => $email,
            'apphost' => getenv('APP_HOST')
            ]) );
    }
}

?>