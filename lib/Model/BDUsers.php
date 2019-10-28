<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BDUsers extends Model
{
    protected $table = 'usuarios';
    public $timestamps = false;

    public function registrarUsuario ($email, $passowrd)
    {
        $this-> email = $email;
        // Encriptar contrase;a con passowrd_hash
        $this-> password = password_hash($passowrd, PASSWORD_DEFAULT);
        $this-> save();
    }

    // Verifica si un email ya está registrado en la BD
    public function emailRegistrado ($email)
    {
        // Busca un usuario donde coincida el email
        $usuario = $this->where('email', $email)->first();

        if(!is_null($usuario['email'])){
            return true;
        }

        return false;
    }

    // Login
    public function verificarUsuario ($email, $passowrd)
    {
        $usuario = $this->where('email', $email)->first();
        
        if(password_verify($passowrd, $usuario->password)){
            return true;
        }

        return false;
    }

    public function posts() {
        return $this->hasMany('App\Model\BDPosts', 'autor', 'email');
    }
}

?>