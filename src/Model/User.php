<?php namespace App\Model;

class User
{
    protected $email;

    function __construct( $email )
    {
        $this->email = $email;
    }

    public function iniciarSesion()
    {
        $_SESSION['user'] = [
            'email' => $this->email
        ];
    }

    public function cerrarSesion()
    {
        unset($_SESSION['user']);
    }
}

?>