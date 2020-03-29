<?php

namespace App\Services;

class AuthService
{
    public function iniciarSesion( $user )
    {
        $_SESSION['user'] = [
            'email' => $user->email
        ];
    }

    public function cerrarSesion()
    {
        unset($_SESSION['user']);
    }
}