<?php namespace App\Routes;

class routerMap {

    static function mapear($map){
        $rutas = Router::obtenerRutasHttp();

        $map->get('index', $rutas['index'],
            [
            'controllerClass' => 'App\Controller\indexController',
            'controllerAction' => 'index'
            ]);
        $map->get('addPosts', $rutas['addPosts'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'index',
            'needsAuth' => true
        ]);
        $map->post('addedPosts', $rutas['addedPosts'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'guardarPost',
            'needsAuth' => true
        ]);
        $map->post('deletePosts', $rutas['dashboard'],
        [
            'controllerClass' => 'App\Controller\bdpostsController',
            'controllerAction' => 'deletePost',
            'needsAuth' => true
        ]);
        $map->get('signup', $rutas['signup'],
        [
            //Los nombres de las clases deben estar en StudlyCaps, primera letra mayúscula
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'index',
            'needsNoSession' => true
        ]);
        $map->post('procesarSignup', $rutas['signup'],
        [
            'controllerClass' => 'App\Controller\SignupController',
            'controllerAction' => 'procesarSignup',
            'needsNoSession' => true
        ]); 
        $map->get('signin', $rutas['signin'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'index',
            'needsNoSession' => true
        ]);
        $map->post('procesarSignin', $rutas['signin'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'procesarSignin',
            'needsNoSession' => true
        ]);
        $map->get('logout', $rutas['logout'],
        [
            'controllerClass' => 'App\Controller\SigninController',
            'controllerAction' => 'logout'
        ]);
        $map->get('dashboard', $rutas['dashboard'],
        [
            'controllerClass' => 'App\Controller\DashboardController',
            'controllerAction' => 'index',
            'needsAuth' => true
        ]);

        // return $map;
    }

}

?>