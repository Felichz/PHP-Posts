<?php namespace App\Controller;

use App\Model\BDPosts;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator as Validator;
use App\Controller\TwigVistas;
use App\routes\routerMap;
use \Exception;

class bdpostsController {

    protected $autor;
    protected $request;
    protected $CONF;
    protected $rutas;
    protected $twigVistas;

    function __construct()
    {
        GLOBAL $request, $CONF;

        $this->autor = $_SESSION['user']['email'];
        $this->request = $request;
        $this->CONF = $CONF;
        $this->rutas = routerMap::obtenerRutasPublicas();
        $this->twigVistas = new TwigVistas;
    }

    public function newPostForm()
    {
        //Renderizar la platilla con Twig
        $response = new HtmlResponse($this->twigVistas->renderizar('nuevoPost.twig.html', [
            'autor' => $this->autor
            ]));
        return $response;
    }

    public function guardarPost() {

        $postData = $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles(); // Desde la super global $_FILES
        $miniatura = $files['miniatura'];
        $nombreMiniatura = time() . '-' . $miniatura->getClientFilename();
        $BDPosts = new BDPosts();
        $validation = new ValidationController;

        // Validar y guardar miniatura
        if ( $validation->validarNuevoPost($postData, $miniatura) )
        {
            $this->guardarMiniatura( $miniatura, $nombreMiniatura );

            // Guardar post en base de datos
            $BDPosts->guardarPost($this->autor, $postData['titulo'], $nombreMiniatura);

            // Enviar respuesta HTML
            $mensaje = 'Publicación realizada con éxito!';
            $response = new HtmlResponse($this->twigVistas->renderizar('nuevoPostRealizado.twig.html', [
                'mensaje' => $mensaje
            ]));
        }
        else
        {
            $mensaje = $validation->errorMessage;

            $response = new HtmlResponse($this->twigVistas->renderizar('nuevoPost.twig.html', [
                'mensaje' => $mensaje,
                'autor' => $this->autor
            ]));
        }
        
        return $response;
    }

    protected function guardarMiniatura( $miniatura, $nombreMiniatura )
    {
        // Crea la carpeta si no existe
        if(!file_exists( $this->CONF['PATH']['UPLOADS'] )) {
            mkdir( $this->CONF['PATH']['UPLOADS'] );
        }

        // Mover imagen a uploads
        $miniatura->moveTo( $this->CONF['PATH']['UPLOADS'] . '/' . $nombreMiniatura);
    }

    public function deletePost()
    {
        $postData = $this->request->getParsedBody();
        $postID = $postData['post'];

        BDPosts::find($postID)->delete();

        return new RedirectResponse( $this->rutas['dashboard'] );
    }
}

?>