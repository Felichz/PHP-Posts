<?php namespace App\Controller;

use App\Interfaces\Vistas;
use App\Interfaces\Validation as ValidationInterface;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use \Respect\Validation\Validator;
use App\Model\User;
use App\routes\routerMap;
use \Exception;

class SigninController
{
    public function __construct($request, Vistas $vistas, ValidationInterface $validation)
    {
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
    }

    public function index ()
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        return $this->renderizar();
    }

    // Se ejecuta si se detecta el método POST
    public function procesarSignin ()
    {
        $request = $this->request;
        $validation = $this->validation;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        $postData = $request->getParsedBody();
        $email = $postData['email'];
        $BDUsers = new BDUsers;

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
        $vistas = $this->vistas;

        return new HtmlResponse($vistas->renderizar('signin.twig.html', [
            'mensaje' => $mensaje
            ]));
    }
}

?>