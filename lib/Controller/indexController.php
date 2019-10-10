<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse;
use App\Controller\TwigVistas;

class indexController {

    public function ejecutarIndexController(){
        $twig = TwigVistas::obtenerTwig();

        //Post de Felix
        $felix = new \App\Model\Usuario('Felix', '12345');
        $model_post = new \App\Model\Post();
        $model_post -> escribirPost($felix -> usuario);

        //Post de Fulanito
        $liz = new Usuario('Fulanito', '54321');
        $controller_post = new Post();
        $controller_post -> escribirPost($liz -> usuario);

        //Saludos
        echo '<hr/>';
        $felix -> saludar();
        $liz -> saludar();
        
        //Cargar autores de los post de la BD
        $bd_post = new \App\Model\BDPosts();
        $posts = $bd_post -> cargarPosts();

        //Renderizar la platilla index con Twig
        //echo $twig->render('index.html', array('autores' => $autores));
        $response = new HtmlResponse($twig->render('index.twig.html', array('posts' => $posts)));
        return $response;
}
}

?>