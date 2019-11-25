<?php namespace App\Interfaces;

interface Validation
{
    public function validarSignup( $email, $password );

    public function validarSignin( $postData );

    public function validarNuevoPost( $postData, $miniatura );
}

?>