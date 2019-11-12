<?php namespace App\Middlewares;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct( array $rutasHttp, string $routeHandlerAttributteName )
    {
        $this->rutasHttp = $rutasHttp;
        $this->routeHandlerAttributteName = $routeHandlerAttributteName;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface
    {
        $routeHandlers = $request->getAttribute( $this->routeHandlerAttributteName );

        $errorPermisos = $this->verificarPermisosRuta( $routeHandlers );

        if ( $errorPermisos == NULL )
        {
            // Pasa a siguientes capas de middleware
            $response = $handler->handle( $request );
        }
        else if ( $errorPermisos == 'needsAuth' ) {
            $response = new RedirectResponse( $this->rutasHttp['signin'] );
        }
        else if( $errorPermisos == 'needsNoSession' ){
            $response = new RedirectResponse( $this->rutasHttp['dashboard'] );
        }

        return $response;
    }

    public function verificarPermisosRuta( $routeHandlers ) 
    {
        $sesionDefinida = isset( $_SESSION['user'] );
        $necesitaAutenticacion = isset( $routeHandlers['needsAuth'] );
        $needsNoSession = isset( $routeHandlers['needsNoSession'] );

        $errorPermisos = NULL;

        if ( $needsNoSession && $sesionDefinida)
        {
            $errorPermisos = 'needsNoSession';
        }
        else if (  $necesitaAutenticacion && !$sesionDefinida ) {
            $errorPermisos = 'needsAuth';
        }

        return $errorPermisos;
    }
}