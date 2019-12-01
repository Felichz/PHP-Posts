<?php namespace App\Routes;

use Zend\Diactoros\Response\EmptyResponse;

class routerMap {

    static function mapear($map){
        $rutas = Router::obtenerRutasHttp();

        ////// Api //////
        $map->get('api.posts.get', $rutas['api.posts.get'],
        [
            'App\Controller\Api\Posts\getController',
            'init'
        ]);

        $map->post('api.posts.post', $rutas['api.posts.post'],
        [
            'App\Controller\Api\Posts\postController',
            'init'
        ]);

        $map->put('api.posts.put', $rutas['api.posts.put'],
        [
            'App\Controller\Api\Posts\putController',
            'init'
        ]);

        $map->delete('api.posts.delete', $rutas['api.posts.delete'],
        [
            'App\Controller\Api\Posts\deleteController',
            'init'
        ]);

        ////// App //////
        $map->get('index', $rutas['index'],
        [
            'App\Controller\indexController',
            'index'
        ]);

        // Contact
        $map->get('contactForm', $rutas['contactForm'],
        [
            'App\Controller\contactController',
            'index'
        ]);
        $map->post('contactSend', $rutas['contactForm'],
        [
            'App\Controller\contactController',
            'send'
        ]);

        // Posts
        $map->get('addPosts', $rutas['addPosts'],
        [
            'App\Controller\bdpostsController',
            'index',
            'needsAuth' => true
        ]);
        $map->post('addedPosts', $rutas['addedPosts'],
        [
            'App\Controller\bdpostsController',
            'guardarPost',
            'needsAuth' => true
        ]);
        $map->post('deletePosts', $rutas['dashboard'],
        [
            'App\Controller\bdpostsController',
            'deletePost',
            'needsAuth' => true
        ]);

        // Users
        $map->get('signup', $rutas['signup'],
        [
            //Los nombres de las clases deben estar en StudlyCaps, primera letra mayúscula
            'App\Controller\SignupController',
            'index',
            'needsNoSession' => true
        ]);
        $map->post('procesarSignup', $rutas['signup'],
        [
            'App\Controller\SignupController',
            'procesarSignup',
            'needsNoSession' => true
        ]); 
        $map->get('signin', $rutas['signin'],
        [
            'App\Controller\SigninController',
            'index',
            'needsNoSession' => true
        ]);
        $map->post('procesarSignin', $rutas['signin'],
        [
            'App\Controller\SigninController',
            'procesarSignin',
            'needsNoSession' => true
        ]);
        $map->get('logout', $rutas['logout'],
        [
            'App\Controller\SigninController',
            'logout'
        ]);
        $map->get('dashboard', $rutas['dashboard'],
        [
            'App\Controller\DashboardController',
            'index',
            'needsAuth' => true
        ]);

    }

}

?>