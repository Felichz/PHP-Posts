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

    public function obtenerUsuario ( $email )
    {
        if ( $this->usuarioRegistrado( $email ) ) {
            return new User( $email )
        }
        else {
            return false;
        }
    }

    // Verifica si un usuario ya está registrado en la BD
    public function usuarioRegistrado ( $email )
    {
        // Busca un usuario donde coincida el email
        $usuario = $this->where('email', $email)->first();

        return !is_null($usuario['email']) ? true : false;
    }

    // Verifica la contraseña del login
    public function verificarPassword ($email, $passowrd)
    {
        $usuario = $this->where('email', $email)->first();
        
        if(password_verify($passowrd, $usuario->password)){
            return true;
        }

        return false;
    }

    public function obtenerPosts( $email ) {
        return $this->where('email', $email)->get()->first()->posts->reverse();
    }
    
    public function posts() {
        return $this->hasMany('App\Model\BDPosts', 'autor', 'email');
    }
}

?>