<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Franzl\Middleware\Whoops\WhoopsRunner;

use Monolog\Logger;

/**
 * Middleware class for using Whoops with a PSR-15 middleware stack
 */
class AppWhoopsMiddleware implements MiddlewareInterface
{
    public function __construct( Logger $log )
    {
        $this->log = $log;
    }

    /**
     * Process an incoming server request and return a response, optionally
     * delegating response creation to a handler.
     */
    public function process(Request $request, Handler $handler): Response
    {
        try {
            return $handler->handle($request);
        }
        catch (\Exception $e) {
            $this->log->warning( 'Whoops: ' . $e->getMessage() );
            return WhoopsRunner::handle($e, $request);
        }
        catch (\Error $e) {
            $this->log->error( 'Whoops: ' . $e->getMessage() );
            return WhoopsRunner::handle($e, $request);
        }
    }
}