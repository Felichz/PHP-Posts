<?php namespace App\Controller;

use App\Interfaces\Vistas;
use App\Interfaces\Validation as ValidationInterface;
use \Psr\Http\Message\ServerRequestInterface;

use \App\Model\BDUsers;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use App\Model\User;
use App\Routes\Router;
use \Exception;

use App\Services\AuthService;

class SignupController
{

    public function __construct($HttpResponse, ServerRequestInterface $request, Vistas $vistas, ValidationInterface $validation )
    {
        $this->HttpResponse = $HttpResponse;
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;

        $this->authService = new AuthService;
    }

    public function index()
    {
        return $this->renderizar();
    }

    public function procesarSignup ()
    {
        $HttpResponse = $this->HttpResponse;
        $request = $this->request;

        $isJson = count($request->getParsedBody()) === 0;

        if ( !$isJson ) {
            $postData = $request->getParsedBody();
        }
        else {
            $json = file_get_contents('php://input');
            $postData = json_decode($json, true);
        }

        $email = $postData['email'];
        $password = $postData['password'];

        $BDUsers = new BDUsers;
        $rutasHttp = Router::obtenerRutasHttp();
        $validation = $this->validation;

        if( $validation->validarSignup($email, $password) ) {

            $BDUsers->registrarUsuario( $email, $password );
            $user = $BDUsers->obtenerUsuario( $email );

            if (!$isJson) {
                
                $this->authService->iniciarSesion( $user );
                return $HttpResponse->RedirectResponse( $rutasHttp['dashboard'] );
            }
            else {
                $jsonResponse = json_encode([
                    'email' => $user->email,
                    'password' => $user->password
                ]);

                return $HttpResponse->HtmlResponse("<pre>{$jsonResponse}</pre>");
            }
        }
        else {
            $mensaje = $validation->errorMessage;

            if ( !$isJson ) {
                // respuesta HTTP HtmlResponse
                return $this->renderizar($mensaje);
            }
            else {
                return $HttpResponse->HtmlResponse(
                    json_encode([
                        'error' => $mensaje
                    ])
                );
            }
        }
    }

    function renderizar ($mensaje = NULL) 
    {
        $HttpResponse = $this->HttpResponse;
        $vistas = $this->vistas;

        return $HttpResponse->HtmlResponse(
            $vistas->renderizar('user/signup.twig.html', [
                'mensaje' => $mensaje
            ])
        );
    }
}

?>