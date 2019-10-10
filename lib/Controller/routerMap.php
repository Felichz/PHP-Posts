<?php namespace App\Controller;

class routerMap {

    static function mapear($map){
        $map->get('index', '/PlatziPHP/', 
            [
            'controllerName' => 'App\Controller\indexController',
            'controllerAction' => 'ejecutarIndexController',
            'controllerParameters' => '$twig'
            ]);
        $map->get('addPosts', '/PlatziPHP/posts/add', 
        [
            'controllerName' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->post('addedPosts', '/PlatziPHP/posts/added', 
        [
            'controllerName' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->get('signup', '/PlatziPHP/user/signup', 
        [
            //Los nombres de las clases deben estar en StudlyCaps, primera letra mayúscula
            'controllerName' => 'App\Controller\SignupController',
            'controllerAction' => 'ejecutarSignupController'
        ]);
        $map->post('procesarSignup', '/PlatziPHP/user/signup', 
        [
            'controllerName' => 'App\Controller\SignupController',
            'controllerAction' => 'procesarSignup'
        ]); 
        $map->get('signin', '/PlatziPHP/user/signin', 
        [
            'controllerName' => 'App\Controller\SigninController',
            'controllerAction' => 'ejecutarSigninController'
        ]);
        $map->post('procesarSignin', '/PlatziPHP/user/signin', 
        [
            'controllerName' => 'App\Controller\SigninController',
            'controllerAction' => 'procesarSignin'
        ]);
        $map->get('logout', '/PlatziPHP/user/logout', 
        [
            'controllerName' => 'App\Controller\SigninController',
            'controllerAction' => 'logout'
        ]);
        $map->get('dashboard', '/PlatziPHP/user/dashboard', 
        [
            'controllerName' => 'App\Controller\DashboardController',
            'controllerAction' => 'ejecutarDashboardController',
            'needsAuth' => true
        ]);

        // return $map;
    }

}

?>