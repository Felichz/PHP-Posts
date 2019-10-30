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
        return $this->renderizar();
    }

    public function procesarSignup ()
    {
        GLOBAL $request;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

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
            return $this->renderizar($mensaje);
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