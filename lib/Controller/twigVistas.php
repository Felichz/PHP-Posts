<?php namespace App\Controller;

// Plantillas Twig
class TwigVistas
{
    static function obtenerTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../lib/Vistas');
        $twig = new \Twig\Environment($loader);

        return $twig;
    }
}

?>