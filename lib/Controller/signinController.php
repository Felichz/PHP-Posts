<?php namespace App\Controller;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use \Respect\Validation\Validator;
use App\Controller\TwigVistas;
use App\Controller\routerMap;
use \Exception;

class SigninController
{
    public function ejecutarSigninController ()
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        
        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new redirectResponse($rutasPublicas['dashboard']);
        }

        return $this->renderizar();
    }

    // Se ejecuta si se detecta el método POST
    public function procesarSignin ()
    {
        GLOBAL $request;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new redirectResponse($rutasPublicas['dashboard']);
        }

        // Validaciones y procesamiento
        try {
            $BDUsers = new BDUsers;

            $postData = $request->getParsedBody();
            $email = $postData['email'];
            $password = $postData['password'];

            // Validar email
            if (!Validator::email()->validate($email)) {
                throw new Exception('Email inválido');
            }

            // verificar que el email esté registrado
            if (!$BDUsers->emailRegistrado($email)) {
                throw new Exception('No existe un usuario con ese email');
            }

            if (!$BDUsers->verificarUsuario($email, $password)) {
                throw new Exception('Datos incorrectos');
            }

            // Logeado con éxito, no se arrojaron Exceptions
            $_SESSION['user'] = [
                'email' => $email
            ];
            return new redirectResponse($rutasPublicas['dashboard']);
        }
        catch (Exception $e) {
            $mensaje = $e->getMessage();
        }

        // Retorna la respuesta HTTP HtmlResponse
        return $this->renderizar($mensaje);
    }

    public function logout () 
    {
        $rutasPublicas = routerMap::obtenerRutasPublicas();
        unset($_SESSION['user']);

        return new redirectResponse($rutasPublicas['signin']);
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