<?php namespace App\Controller;

use App\Controller\routerMap;

// Plantillas Twig
class TwigVistas
{
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../lib/Vistas');
        $this->twigObject = new \Twig\Environment($loader);
    }

    public function renderizar(string $plantilla, array $parametrosUsuario = NULL)
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        // App Address es la ruta a la carpeta principal raiz de donde
        // buscar los archivos desde el HTML o hacer redirecciones
        // desde el cliente publico
        if(getenv('APP_DIR')){
            $appAddress = 'http://' . $_SERVER['HTTP_HOST'] . '/' . getenv('APP_DIR') . '/';
        }
        else {
            $appAddress = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        }

        $parametrosDefault = [
            'rutas' => $rutasPublicas,
            'appAddress' => $appAddress
        ];

        $parametros = array_merge($parametrosUsuario, $parametrosDefault);

        $render = $this->twigObject->render($plantilla, $parametros);

        return $render;
    }
}

?>