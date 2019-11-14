<?php namespace App\Routes;

use App\Conf\Conf;

class HttpRoutes
{
    // Rutas http publicas accesibles desde el cliente

    static function obtenerRutasHttp ()
    {
        $CONF = Conf::getConf();

        if ( empty($CONF['APP_URI']) ){
            $dir = '/';
        }
        else {
            $dir = '/' . $CONF['APP_URI'] . '/';
        }

        $rutasHttp = [
            'index' => $dir,
            'addPosts' => $dir . 'post/add',
            'addedPosts' => $dir . 'post/added',
            'signup' => $dir . 'user/signup',
            'signin' => $dir . 'user/signin',
            'logout' => $dir . 'user/logout',
            'dashboard' => $dir . 'user/dashboard',
            'uploads' => $dir . 'uploads',
            'img' => $dir . 'img'
        ];

        return $rutasHttp;
    }
}

?>