<?php namespace App\Controller;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use \Respect\Validation\Validator;
use App\Controller\TwigVistas;
use \Exception;

class SignupController
{
    public function ejecutarSignupController ()
    {
        $twig = TwigVistas::obtenerTwig();

        // Si ya hay un usuario logeado en la sesion entonces envía una redirección
        if ( isset($_SESSION['user']) ) {
            return new RedirectResponse('/PlatziPHP/user/dashboard');
        }

        return $this->renderizar($twig);
    }

    // Se ejecuta si se detecta el método POST, también ejecuta la funcion principal,
    // la funcion base 'ejecutarSignupController' la cual renderiza lo visual
    public function procesarSignup ()
    {
        GLOBAL $request;
        $twig = TwigVistas::obtenerTwig();

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

            // Validar contraseña por pasos para poder dar un mensaje más específico
            if (!Validator::alnum()->noWhitespace()->validate($password)) {
                throw new Exception('La contraseña solo admite carácteres alfanuméricos y sin espacios en blanco');
            }
            if (!Validator::length(5, null)->Validate($password)) {
                throw new Exception('La contraseña debe contener al menos 5 caracteres');
            }
            if (!Validator::length(null, 250)->Validate($password)) {
                throw new Exception('Contraseña demasiado larga');
            }

            // Y por último antes de procesar el registro,
            // verificar que el email aún no esté registrado
            if ($BDUsers->emailRegistrado($email)) {
                throw new Exception('Email ya registrado');
            }

            // No se arrojaron Exceptions, se realiza registro
            $BDUsers->registrarUsuario($email, $password);
            // Inicia la sesion y redirecciona al dashboard
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

    function renderizar ($twig, $mensaje = NULL) 
    {
        return new HtmlResponse($twig->render('signup.twig.html', ['mensaje' => $mensaje]));;
    }
}

?>