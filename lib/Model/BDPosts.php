<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BDPosts extends Model
{
    protected $table = 'posts';

    public function guardarPost($autor, $titulo, $nombreMiniatura)
    {
        $this->autor = $autor;
        $this->titulo = $titulo;
        $this->miniatura = $nombreMiniatura;
        $this->save();
    }

    public function borrarPost( $id )
    {
        $this->find($id)->delete();
    }
}

?>