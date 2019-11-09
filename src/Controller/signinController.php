<?php namespace App\Controller;

use App\Interfaces\Vistas;
use App\Interfaces\Validation as ValidationInterface;
use \Psr\Http\Message\ServerRequestInterface;

use App\Model\BDUsers;
use App\Model\User;
use App\Routes\Router;
use \Exception;

class SigninController
{
    public function __construct($HttpResponse, ServerRequestInterface $request, Vistas $vistas, ValidationInterface $validation)
    {
        $this->HttpResponse = $HttpResponse;
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
    }

    public function index ()
    {
        $rutasHttp = Router::obtenerRutasHttp();

        return $this->renderizar();
    }

    // Se ejecuta si se detecta el método POST
    public function procesarSignin ()
    {
        $HttpResponse = $this->HttpResponse;
        $request = $this->request;
        $validation = $this->validation;
        $rutasHttp = Router::obtenerRutasHttp();

        $postData = $request->getParsedBody();
        $email = $postData['email'];
        $BDUsers = new BDUsers;

        if( $validation->validarSignin($postData) ) {

        $user = $BDUsers->obtenerUsuario( $email );
        $user->iniciarSesion();

        return $HttpResponse->RedirectResponse($rutasHttp['dashboard']);
        
        }
        else {
            $mensaje = NULL;
            $mensaje = $validation->errorMessage;

            // respuesta HTTP HtmlResponse
            return $this->renderizar($mensaje);
        }
    }

    public function logout () 
    {
        $HttpResponse = $this->HttpResponse;
        $rutasHttp = Router::obtenerRutasHttp();

        $user = new User( $_SESSION['user']['email'] );
        $user->cerrarSesion();

        return $HttpResponse->RedirectResponse( $rutasHttp['index'] );
    }

    public function renderizar (string $mensaje = NULL)
    {
        $vistas = $this->vistas;
        $HttpResponse = $this->HttpResponse;

        return $HttpResponse->HtmlResponse(
            $vistas->renderizar('signin.twig.html', [
                'mensaje' => $mensaje
            ])
        );
    }
}

?>