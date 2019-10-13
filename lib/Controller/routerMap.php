<?php namespace App\Controller;

class routerMap {

    // Las rutas publicas son las accesibles desde el cliente
    // En esta app se suelen utilizar para hacer redirecciones http al cliente
    static function obtenerRutasPublicas()
    {
        GLOBAL $CONF;

        if ( empty($CONF['APP_DIR']) ){
            $dir = '/';
        }
        else {
            $dir = '/' . $CONF['APP_DIR'] . '/';
        }

        $rutasPublicas = [
            'index' => $dir,
            'addPosts' => $dir . 'posts/add',
            'addedPosts' => $dir . 'posts/added',
            'signup' => $dir . 'user/signup',
            'procesarSignup' => $dir . 'user/signup',
            'signin' => $dir . 'user/signin',
            'procesarSignin' => $dir . 'user/signin',
            'logout' => $dir . 'user/logout',
            'dashboard' => $dir . 'user/dashboard',
            'uploads' => $dir . 'uploads'
        ];

        return $rutasPublicas;
    }

    static function mapear($map){
        $rutas = self::obtenerRutasPublicas();

        $map->get('index', $rutas['index'],
            [
            'controllerClass' => 'App\Controller\indexController',
            'controllerAction' => 'ejecutarIndexController',
            'controllerParameters' => '$twig'
            ]);
        $map->get('addPosts', $rutas['addPosts'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->post('addedPosts', $rutas['addedPosts'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->get('signup', $rutas['signup'],
        [
            //Los nombres de las clases deben estar en StudlyCaps, primera letra mayúscula
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'ejecutarSignupController'
        ]);
        $map->post('procesarSignup', $rutas['procesarSignup'],
        [
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'procesarSignup'
        ]); 
        $map->get('signin', $rutas['signin'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'ejecutarSigninController'
        ]);
        $map->post('procesarSignin', $rutas['procesarSignin'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'procesarSignin'
        ]);
        $map->get('logout', $rutas['logout'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'logout'
        ]);
        $map->get('dashboard', $rutas['dashboard'],
        [
            'controllerClass' => 'App\Controller\DashboardController',
            'controllerAction' => 'ejecutarDashboardController',
            'needsAuth' => true
        ]);

        // return $map;
    }

}

?>