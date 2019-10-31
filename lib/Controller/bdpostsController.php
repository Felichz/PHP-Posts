<?php namespace App\Controller;

use App\Interfaces\Vistas;
use App\Interfaces\Validation as ValidationInterface;

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
    protected $BDPosts;

    function __construct( $request, Vistas $vistas, ValidationInterface $validation)
    {
        GLOBAL $CONF;

        $this->request = $request;
        $this->vistas = $vistas;
        $this->validation = $validation;
        $this->BDPosts = new BDPosts;

        $this->autor = $_SESSION['user']['email'];
        $this->CONF = $CONF;
        $this->rutas = routerMap::obtenerRutasPublicas();

    }

    public function index()
    {
        //Renderizar la platilla con Twig
        $response = new HtmlResponse(
            $this->vistas->renderizar('nuevoPost.twig.html', [
                'autor' => $this->autor
            ])
        );
        return $response;
    }

    public function guardarPost() {

        $request = $this->request;
        $vistas = $this->vistas;
        $validation = $this->validation;
        $BDPosts = $this->BDPosts;

        $postData = $request->getParsedBody();
        $files = $request->getUploadedFiles(); // Desde la super global $_FILES
        $miniatura = $files['miniatura'];

        $nombreMiniatura = time() . '-' . $miniatura->getClientFilename();

        // Validar y guardar miniatura
        if ( $validation->validarNuevoPost($postData, $miniatura) )
        {
            $this->guardarMiniatura( $miniatura, $nombreMiniatura );

            // Guardar post en base de datos
            $BDPosts->guardarPost($this->autor, $postData['titulo'], $nombreMiniatura);

            // Enviar respuesta HTML
            $mensaje = 'Publicación realizada con éxito!';
            $response = new HtmlResponse(
                $vistas->renderizar('nuevoPostRealizado.twig.html', [
                    'mensaje' => $mensaje
                ])
            );
        }
        else
        {
            $mensaje = $validation->errorMessage;

            $response = new HtmlResponse(
                $vistas->renderizar('nuevoPost.twig.html', [
                    'mensaje' => $mensaje,
                    'autor' => $this->autor
                ])
            );
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

        $this->BDPosts->borrarPost( $postID );

        return new RedirectResponse( $this->rutas['dashboard'] );
    }
}

?>