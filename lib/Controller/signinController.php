<?php namespace App\Controller;

use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use \App\Model\BDUsers;
use \Respect\Validation\Validator;
use \Exception;

class SigninController
{
    public function ejecutarSigninController ($twig, $mensaje = NULL)
    {
        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new RedirectResponse('/PlatziPHP/user/dashboard');
        }

        return $this->renderizar($twig, $mensaje);
    }

    // Se ejecuta si se detecta el método POST, también ejecuta la funcion principal,
    // la funcion base 'ejecutarSigninController' la cual renderiza lo visual
    public function procesarSignin ($request, $twig)
    {
        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new RedirectResponse('/PlatziPHP/user/dashboard');
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

            // Logeado con éxito, pasaron las validaciones
            $_SESSION['user'] = [
                'email' => $email
            ];
            return new RedirectResponse('/PlatziPHP/user/dashboard');
        }
        catch (Exception $e) {
            $mensaje = $e->getMessage();
        }

        // Retorna la respuesta HTTP HtmlResponse
        return $this->renderizar($twig, $mensaje);
    }

    public function logout () 
    {
        unset($_SESSION['user']);

        return new RedirectResponse('/PlatziPHP/user/signin');
    }

    public function renderizar ($twig, $mensaje)
    {
        return new HtmlResponse($twig->render('signin.twig.html', ['mensaje' => $mensaje]));
    }
}

?>