<?php namespace App\Traits;

use App\Conf\Conf;
use App\Routes\Router;

trait Miniatura
{
    public function obtenerMiniatura ( $miniatura )
    {
        $rutasHttp = Router::obtenerRutasHttp();
        $CONF = Conf::getConf();

        if ( file_exists( $CONF['PATH']['UPLOADS'] . '/' . $miniatura ) )
        {
            return $rutasHttp['uploads'] . '/' . $miniatura;
        }
        else
        {
            return $rutasHttp['img'] . '/miniatura-default.jpg';
        }
    }
}