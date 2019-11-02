<?php namespace App\Controller;

use App\Interfaces\Vistas;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorMessageController
{
    public function __construct($HttpResponse, Vistas $vistas )
    {
        GLOBAL $CONF;
        $this->CONF = $CONF;
        $this->HttpResponse = $HttpResponse;
        $this->vistas = $vistas;
    }

    public function index( \Exception $e )
    {
        $CONF = $this->CONF;
        $HttpResponse = $this->HttpResponse;
        $vistas = $this->vistas;

        if ( $CONF['DEBUG'] == true | $e->getCode() == 1 )
        {
            $errorMessage = $e->getMessage(); // Mostrar Exception debug
        }
        else {        
            $errorMessage = 'An error has ocurred'; // Ocultar Exception debug
        }

        return $HttpResponse->HtmlResponse(
            $vistas->renderizar('error.twig.html', [
                'errorMessage' => $errorMessage
            ])
        );
        
    }
}

?>