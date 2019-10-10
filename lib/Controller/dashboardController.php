<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;

class DashboardController
{
    function ejecutarDashboardController($twig)
    {
        $email = $_SESSION['user']['email'];
        return new HtmlResponse( $twig->render('dashboard.twig.html', ['email' => $email]) );
    }
}

?>