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
            // App
            'index' => $dir,

            // Contact
            'contactForm' => $dir . 'contact',

            // Posts
            'addPosts' => $dir . 'post/add',
            'addedPosts' => $dir . 'post/added',

            // Users
            'signup' => $dir . 'user/signup',
            'signin' => $dir . 'user/signin',
            'logout' => $dir . 'user/logout',
            'dashboard' => $dir . 'user/dashboard',

            // Public files
            'uploads' => $dir . 'uploads',
            'img' => $dir . 'img'
        ];

        return $rutasHttp;
    }
}

?>