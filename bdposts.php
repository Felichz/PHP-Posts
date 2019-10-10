<?php

use App\Model\BDPosts;

if(!empty($_POST))
{
    $NuevoPost = new BDPosts();
    $NuevoPost -> autor = $_POST['autor'];
    $NuevoPost -> save();
    echo '<h2>Publicación realizada con éxito!</h2>';
    echo '<a href="../">Volver</a>';
}
else
{
    include 'lib/Vistas/nuevo_post.html';
}

?>