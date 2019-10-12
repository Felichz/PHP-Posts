<?php namespace App\Controller;

class routerMap {

    public static $rutasPublicas = [
        'index' => '/',
        'addPosts' => '/posts/add',
        'addedPosts' => '/posts/added',
        'signup' => '/user/signup',
        'procesarSignup' => '/user/signup',
        'signin' => '/user/signin',
        'procesarSignin' => '/user/signin',
        'logout' => '/user/logout',
        'dashboard' => '/user/dashboard',
        'uploads' => '/public/uploads'
    ];

    static function obtenerRutasPublicas()
    {
        $rutasPublicas = self::$rutasPublicas;
        $rutas = array();

        $dir = '/' . getenv('APP_DIR');

        foreach ($rutasPublicas as $k => $v) {
            $rutas[$k] = $dir . $v;
        }

        return $rutas;
    }

    static function obtenerRutasServer()
    {
        // Este archivo debe estar en /lib/Controller/
        $raiz = __DIR__ . '/../../';

        $rutasServer = [
            'uploads' => $raiz . 'public/uploads'
        ];

        return $rutasServer;
    }

    static function mapear($map){
        $rutas = self::$rutasPublicas;
        $dir = '/' . getenv('APP_DIR');

        $map->get('index', $dir . $rutas['index'],
            [
            'controllerClass' => 'App\Controller\indexController',
            'controllerAction' => 'ejecutarIndexController',
            'controllerParameters' => '$twig'
            ]);
        $map->get('addPosts', $dir . $rutas['addPosts'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->post('addedPosts', $dir . $rutas['addedPosts'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->get('signup', $dir . $rutas['signup'],
        [
            //Los nombres de las clases deben estar en StudlyCaps, primera letra mayúscula
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'ejecutarSignupController'
        ]);
        $map->post('procesarSignup', $dir . $rutas['procesarSignup'],
        [
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'procesarSignup'
        ]); 
        $map->get('signin', $dir . $rutas['signin'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'ejecutarSigninController'
        ]);
        $map->post('procesarSignin', $dir . $rutas['procesarSignin'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'procesarSignin'
        ]);
        $map->get('logout', $dir . $rutas['logout'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'logout'
        ]);
        $map->get('dashboard', $dir . $rutas['dashboard'],
        [
            'controllerClass' => 'App\Controller\DashboardController',
            'controllerAction' => 'ejecutarDashboardController',
            'needsAuth' => true
        ]);

        // return $map;
    }

}

?>