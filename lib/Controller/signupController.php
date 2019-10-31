<?php namespace App\Controller;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use App\Model\User;
use App\routes\routerMap;
use \Exception;

class SignupController
{

    public function __construct( $request, $vistas, $validation )
    {
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
    }

    public function index ()
    {
        return $this->renderizar();
    }

    public function procesarSignup ()
    {
        $request = $this->request;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        $postData = $request->getParsedBody();
        $email = $postData['email'];
        $password = $postData['password'];
        $BDUsers = new BDUsers;
        $validation = $this->validation;

        if( $validation->validarSignup($postData) ) {

            $BDUsers->registrarUsuario( $email, $password );
            $user = $BDUsers->obtenerUsuario( $email );
            $user->iniciarSesion();
            
            return new redirectResponse( $rutasPublicas['dashboard'] );
        }
        else {
            $mensaje = $validation->errorMessage;

            // respuesta HTTP HtmlResponse
            return $this->renderizar($mensaje);
        }
    }

    function renderizar ($mensaje = NULL) 
    {
        $vistas = $this->vistas;

        return new HtmlResponse($vistas->renderizar('signup.twig.html', [
            'mensaje' => $mensaje
        ]));
    }
}

?>