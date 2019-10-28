<?php namespace App\Controller;

use App\Model\BDPosts;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator as Validator;
use App\Controller\TwigVistas;
use App\Controller\routerMap;
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

        /* 
            Objeto request de interfaces estándar PSR-7
        */

        // Validar entrada de datos con librería respect/validation

        // Si la validación dentro del bloque try falla, el validador arroja una exepción la cual
        // es captada y manejada sin detener el flujo de la aplicación
        try {
            // Obtener datos de formulario POST
            $postData = $this->request->getParsedBody();
            
            $files = $this->request->getUploadedFiles(); // Desde la super global $_FILES
            $miniatura = $files['miniatura'];
            $nombreMiniatura = time() . '-' . $miniatura->getClientFilename();

            // Validar y guardar miniatura
            $this->validarForm( $postData, $miniatura );
            $this->guardarMiniatura( $miniatura, $nombreMiniatura );

            // Guardar post en base de datos
            $BDPosts = new BDPosts();
            $BDPosts->guardarPost($this->autor, $postData['titulo'], $nombreMiniatura);

            // Enviar respuesta HTML
            $mensaje = 'Publicación realizada con éxito!';
            $response = new HtmlResponse($this->twigVistas->renderizar('nuevoPostRealizado.twig.html', [
                'mensaje' => $mensaje
                ]));

        } catch (Exception $e) {
            $mensaje = $e->getMessage();

            $response = new HtmlResponse($this->twigVistas->renderizar('nuevoPost.twig.html', [
                'mensaje' => $mensaje,
                'autor' => $this->autor
            ]));
        }
        
        return $response;
    }

    protected function validarForm( $postData, $miniatura )
    {
        // Titulo
        if ( !Validator::notEmpty()->validate($postData['titulo']) ) {
            throw new Exception('El título no puede estar vacío');
        }
        if ( !Validator::length(null, 250)->validate($postData['titulo']) ) {
            throw new Exception('Título demasiado largo');
        }

        // Miniatura
        if($_FILES['miniatura']['name'] == ''){
            throw new Exception('Se debe subir una miniatura');
        }

        if($miniatura->getError() != UPLOAD_ERR_OK){
            throw new Exception('Error al guardar miniatura');
        }
    }

    protected function guardarMiniatura( $miniatura, $nombreMiniatura )
    {
        // Crea la carpeta si no existe
        if(!file_exists('../public/uploads')) {
            mkdir('../public/uploads');
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