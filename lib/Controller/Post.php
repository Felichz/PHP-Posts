<?php namespace App\Controller;

class Post
{
    public $titulo;
    public $contenido;

    public function __construct()
    {
        $this -> titulo = '<h2 style="margin-bottom:-5px;">Este es un post de <u>Controller</u></h2>';
        $this -> contenido = '<h3>Contenido post de Controller</h3>';
    }

    public function escribirPost(string $autor)
    {
        echo '<hr/>';
        echo $this -> titulo;
        echo '<i>Autor: ' . $autor . '</i>';
        echo $this -> contenido;
    }

}

?>