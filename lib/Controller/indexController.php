<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;
use App\routes\routerMap;
use \App\Model\BDPosts;

class indexController {

    public function ejecutarIndexController(){
        $twigVistas = new TwigVistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        
        // Cargar autores de los post de la BD
        $posts = BDPosts::all()->reverse();

        // Solicitar parrafos a API lorem ipsum
        $ipsum[1] = file_get_contents('https://loripsum.net/api/1/long/plaintext');
        $ipsum[2] = file_get_contents('https://loripsum.net/api/1/medium/plaintext');

        // Obtener email de usuario
        $email = $_SESSION['user']['email'] ?? false;

        // Renderizar la platilla index con Twig
        $response = new HtmlResponse($twigVistas->renderizar('index.twig.html', [
            'posts' => $posts,
            'ipsum' => $ipsum,
            'email' => $email
            ]));
        return $response;
}
}

?>