<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\Controller\routerMap;

class indexController {

    public function ejecutarIndexController(){
        $twigVistas = new TwigVistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        
        // Cargar autores de los post de la BD
        $bd_post = new \App\Model\BDPosts();
        $posts = $bd_post -> cargarPosts();

        // Renderizar la platilla index con Twig
        $response = new HtmlResponse($twigVistas->renderizar('index.twig.html', [
            'posts' => $posts
            ]));
        return $response;
}
}

?>