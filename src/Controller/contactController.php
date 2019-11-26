<?php namespace App\Controller;

use App\Interfaces\Mailer as MailerInterface;
use App\Interfaces\Validation as ValidationInterface;
use App\Interfaces\Vistas;
use App\Model\Messages;
use App\Services\AsyncCommand;
use App\Services\HttpResponse;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class contactController
{
    protected $httpResponse;
    protected $request;
    protected $vistas;
    protected $validation;

    public function __construct( HttpResponse $httpResponse, ServerRequestInterface $request, Vistas $vistas, ValidationInterface $validation, array $CONF, MailerInterface $mailer, AsyncCommand $asyncCommand )
    {
        $this->httpResponse = $httpResponse;
        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
        $this->CONF = $CONF;
        $this->mailer = $mailer;
        $this->asyncCommand = $asyncCommand;

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
            $mailId = $this->messages->guardarMensaje( $postData['email'], $postData['nombre'], $postData['message'] );

            // Ejecutar comando para enviar Emails de manera asincrona
            $rutaBin = $this->CONF['PATH']['BIN'];
            $this->asyncCommand->ejecutarComando("php {$rutaBin}/console send-mail {$mailId}"); 

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