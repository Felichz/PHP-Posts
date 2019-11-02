<?php namespace App\Interfaces;

interface Router
{
    // Devuelve objeto router a partir del objeto HTTP Request
    static function procesarRequest( $request );

    // Devuelve array asociativo con nombres de ruta sus respectivas URLs HTTP
    static function obtenerRutasHttp();
}

?>