<?php namespace App\Controller;

class Usuario
{
    public $usuario;
    private $clave;

    public function __construct(string $u, string $c)
    {
        $this -> usuario = $u;
        $this -> clave = $c;
    }

    public function saludar()
    {
        echo 'Hola soy ' . $this -> usuario . ' de Controller! <br/>';
    }

    private function cambiarClave(integer $c)
    {
        $this -> clave = $c;
    }
}

?>