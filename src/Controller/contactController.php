<?php namespace App\Controller;

use App\Interfaces\Mailer as MailerInterface;
use App\Interfaces\Validation as ValidationInterface;
use App\Interfaces\Vistas;
use App\Model\Messages;
use App\Services\HttpResponse;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class contactController
{
    protected $httpResponse;
    protected $request;
    protected $vistas;
    protected $validation;

    public function __construct( HttpResponse $httpResponse, ServerRequestInterface $request, Vistas $vistas, ValidationInterface $validation, array $CONF, MailerInterface $mailer )
    {
        $this->httpResponse = $httpResponse;
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
        $this->CONF = $CONF;
        $this->mailer = $mailer;

        $this->messages = new Messages;
    }

    public function index ()
    {
        $html = $this->vistas->renderizar('contact/contacto.twig');
        return $this->httpResponse->HtmlResponse( $html );
    }

    public function send ()
    {
        $alertDanger = null;
        $alertSuccess = null;
        $old = array();

        $postData = $this->request->getParsedBody();

        if ( $this->validation->validarContacto($postData) ) {
            $this->messages->guardarMensaje( $postData['email'], $postData['nombre'], $postData['message'] );

            // Ejecutar comando para enviar Emails de manera asincrona
            $rutaBin = str_replace('\\', '/', $this->CONF['PATH']['BIN']);
            $rutaLog = str_replace('\\', '/', $this->CONF['PATH']['LOG']);

            if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
                $comando = 'start /B php ' . $rutaBin . '/console send-mail > ' . $rutaLog . '/cmd-output.log"';
                pclose(popen($comando, 'r'));
            }
            else {
                $comando = '/usr/bin/nohup php ' . $rutaBin . '/console send-mail >' . $rutaLog . '/shell-output.log 2>&1 &';
                shell_exec($comando);
            }

            $alertSuccess = 'Email enviado con éxito';
        }
        else {
            $alertDanger = $this->validation->errorMessage;

            $old['email'] = $postData['email'];
            $old['mensaje'] = $postData['message'];
        }

        $html = $this->vistas->renderizar('contact/contacto.twig', [
            'alertDanger' => $alertDanger,
            'alertSuccess' => $alertSuccess,
            'old' => $old
        ]);

        return $this->httpResponse->HtmlResponse( $html );
    }
}