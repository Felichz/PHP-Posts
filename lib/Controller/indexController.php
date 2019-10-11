<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;

class indexController {

    public function ejecutarIndexController(){
        $twig = TwigVistas::obtenerTwig();
        
        //Cargar autores de los post de la BD
        $bd_post = new \App\Model\BDPosts();
        $posts = $bd_post -> cargarPosts();

        //Renderizar la platilla index con Twig
        //echo $twig->render('index.html', array('autores' => $autores));
        $response = new HtmlResponse($twig->render('index.twig.html', [
            'posts' => $posts,
            'apphost' => getenv('APP_HOST')
            ]));
        return $response;
}
}

?>