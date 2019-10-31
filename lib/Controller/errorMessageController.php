<?php namespace App\Controller;

use App\Interfaces\Vistas;
use App\routes\routerMap;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorMessageController
{
    public function __construct( Vistas $vistas )
    {
        GLOBAL $CONF;
        $this->CONF = $CONF;
        $this->vistas = $vistas;
    }

    public function index( \Exception $e )
    {
        $CONF = $this->CONF;
        $vistas = $this->vistas;

        if ( $CONF['DEBUG'] == true | $e->getCode() == 1 )
        {
            $errorMessage = $e->getMessage(); // Mostrar Exception debug
        }
        else {        
            $errorMessage = 'An error has ocurred'; // Ocultar Exception debug
        }

        return new HtmlResponse(
            $vistas->renderizar('error.twig.html', [
                'errorMessage' => $errorMessage
            ])
        );
        
    }
}

?>