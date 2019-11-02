<?php namespace App\Controller;

use App\Interfaces\Vistas;
use App\Interfaces\Validation as ValidationInterface;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use App\Model\User;
use App\Routes\Router;
use \Exception;

class SignupController
{

    public function __construct($HttpResponse, $request, Vistas $vistas, ValidationInterface $validation )
    {
        $this->HttpResponse = $HttpResponse;
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
    }

    public function index()
    {
        return $this->renderizar();
    }

    public function procesarSignup ()
    {
        $HttpResponse = $this->HttpResponse;
        $request = $this->request;
        $rutasHttp = Router::obtenerRutasHttp();

        $postData = $request->getParsedBody();
        $email = $postData['email'];
        $password = $postData['password'];
        $BDUsers = new BDUsers;
        $validation = $this->validation;

        if( $validation->validarSignup($postData) ) {

            $BDUsers->registrarUsuario( $email, $password );
            $user = $BDUsers->obtenerUsuario( $email );
            $user->iniciarSesion();
            
            return $HttpResponse->RedirectResponse( $rutasHttp['dashboard'] );
        }
        else {
            $mensaje = $validation->errorMessage;

            // respuesta HTTP HtmlResponse
            return $this->renderizar($mensaje);
        }
    }

    function renderizar ($mensaje = NULL) 
    {
        $HttpResponse = $this->HttpResponse;
        $vistas = $this->vistas;

        return $HttpResponse->HtmlResponse(
            $vistas->renderizar('signup.twig.html', [
                'mensaje' => $mensaje
            ])
        );
    }
}

?>