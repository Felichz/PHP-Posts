<?php namespace App\Model;

use App\Conf\Conf;
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
        $CONF = Conf::getConf();

        $post = $this->findOrFail($id);
        $post->delete();

        // Borrar archivo miniatura
        $miniatura = $CONF['PATH']['UPLOADS'] . '/' . $post->miniatura;
        if( file_exists($miniatura) )
        {
            unlink( $miniatura );
        }
    }

    public function cargarPosts()
    {
        return $this->all()->reverse();
    }
}

?>