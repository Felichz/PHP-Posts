<?php namespace App\Interfaces;

interface Validation
{
    public function validarSignup( $postData );

    public function validarSignin( $postData );

    public function validarNuevoPost( $postData, $miniatura );
}

?>