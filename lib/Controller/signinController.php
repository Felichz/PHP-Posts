<?php namespace App\Controller;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use \Respect\Validation\Validator;
use App\Controller\TwigVistas;
use App\Model\User;
use App\routes\routerMap;
use \Exception;

class SigninController
{
    public function index ()
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        return $this->renderizar();
    }

    // Se ejecuta si se detecta el método POST
    public function procesarSignin ()
    {
        GLOBAL $request;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        $postData = $request->getParsedBody();
        $email = $postData['email'];
        $BDUsers = new BDUsers;
        $validation = new ValidationController;

        if( $validation->validarSignin($postData) ) {

        $user = $BDUsers->obtenerUsuario( $email );
        $user->iniciarSesion();

        return new redirectResponse($rutasPublicas['dashboard']);
        
        }
        else {
            $mensaje = $validation->errorMessage;

            // respuesta HTTP HtmlResponse
            return isset( $mensaje ) ? $this->renderizar($mensaje) : $this->renderizar();
        }
    }

    public function logout () 
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        $user = new User( $_SESSION['user']['email'] );
        $user->cerrarSesion();

        return new redirectResponse( $rutasPublicas['index'] );
    }

    public function renderizar (string $mensaje = NULL)
    {
        $twigVistas = new TwigVistas;

        return new HtmlResponse($twigVistas->renderizar('signin.twig.html', [
            'mensaje' => $mensaje
            ]));
    }
}

?>