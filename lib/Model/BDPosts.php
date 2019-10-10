<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BDPosts extends Model
{
    protected $table = 'posts';

    public function cargarPosts()
    {
        $registros = $this -> all();
        foreach ($registros as $k => $registro)
        {
            $posts[$k]['titulo'] = $registro->titulo;
            $posts[$k]['autor'] = $registro->autor;
            $posts[$k]['miniatura'] = $registro->miniatura;
        }

        return $posts;
    }
}

?>