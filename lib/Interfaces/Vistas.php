<?php namespace App\Interfaces;

interface Vistas
{
    // Retorna string con el HTML de la plantilla renderizada
    public function renderizar( string $plantilla, array $parametrosUsuario = NULL );
}

?>