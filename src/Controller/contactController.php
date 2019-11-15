<?php namespace App\Controller;

use App\Interfaces\Mailer as MailerInterface;
use App\Interfaces\Validation as ValidationInterface;
use App\Interfaces\Vistas;
use App\Services\HttpResponse;
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
            $alertSuccess = 'Email enviado con éxito';

            $this->mailer->sendMail([
                'to' => $this->CONF['EMAIL']['APP_EMAIL'],
                'from' => $postData['email'],
                'replyTo' => $postData['email'],
                'subject' => 'Formulario de contacto PHPAvanzado',
                'body' => $postData['message']
            ]);
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