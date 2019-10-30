<?php namespace App\Controller;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use App\Controller\TwigVistas;
use App\Model\User;
use App\routes\routerMap;
use \Exception;

class SignupController
{
    public function index ()
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new redirectResponse($rutasPublicas['dashboard']);
        }

        return $this->renderizar();
    }

    // Se ejecuta si se detecta el método POST, también ejecuta la funcion principal,
    // la funcion base 'ejecutarSignupController' la cual renderiza lo visual
    public function procesarSignup ()
    {
        GLOBAL $request;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new redirectResponse($rutasPublicas['dashboard']);
        }

        $postData = $request->getParsedBody();
        $email = $postData['email'];
        $password = $postData['password'];
        $BDUsers = new BDUsers;
        $validation = new ValidationController;

        if( $validation->validarSignup($postData) ) {

            $BDUsers->registrarUsuario( $email, $password );
            $user = $BDUsers->obtenerUsuario( $email );
            $user->iniciarSesion();
            
            return new redirectResponse( $rutasPublicas['dashboard'] );
        }
        else {
            $mensaje = $validation->errorMessage;

            // respuesta HTTP HtmlResponse
            return isset( $mensaje ) ? $this->renderizar($mensaje) : $this->renderizar();
        }
    }

    function renderizar ($mensaje = NULL) 
    {
        $twigVistas = new TwigVistas;

        return new HtmlResponse($twigVistas->renderizar('signup.twig.html', [
            'mensaje' => $mensaje
        ]));
    }
}

?>