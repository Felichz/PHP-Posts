<?php namespace App\Controller;

class routerMap {

    static function mapear($map){
        $map->get('index', getenv('APP_HOST'), 
            [
            'controllerClass' => 'App\Controller\indexController',
            'controllerAction' => 'ejecutarIndexController',
            'controllerParameters' => '$twig'
            ]);
        $map->get('addPosts', getenv('APP_HOST') . 'posts/add', 
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->post('addedPosts', getenv('APP_HOST') . 'posts/added', 
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'ejecutarBDPostsController'
        ]);
        $map->get('signup', getenv('APP_HOST') . 'user/signup', 
        [
            //Los nombres de las clases deben estar en StudlyCaps, primera letra mayúscula
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'ejecutarSignupController'
        ]);
        $map->post('procesarSignup', getenv('APP_HOST') . 'user/signup', 
        [
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'procesarSignup'
        ]); 
        $map->get('signin', getenv('APP_HOST') . 'user/signin', 
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'ejecutarSigninController'
        ]);
        $map->post('procesarSignin', getenv('APP_HOST') . 'user/signin', 
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'procesarSignin'
        ]);
        $map->get('logout', getenv('APP_HOST') . 'user/logout', 
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'logout'
        ]);
        $map->get('dashboard', getenv('APP_HOST') . 'user/dashboard', 
        [
            'controllerClass' => 'App\Controller\DashboardController',
            'controllerAction' => 'ejecutarDashboardController',
            'needsAuth' => true
        ]);

        // return $map;
    }

}

?>