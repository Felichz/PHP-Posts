<?php namespace App\Controller;

use App\Model\BDPosts;
use \Zend\Diactoros\Response\HtmlResponse; // PSR-7
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator as Validator;
use App\Controller\TwigVistas;
use App\Controller\routerMap;
use \Exception;

class bdpostsController {

    public function ejecutarBdpostsController() {
        
        GLOBAL $request, $CONF;
        $twigVistas = new TwigVistas;
        $rutasPublicas = routerMap::obtenerRutasPublicas();

        /* 
            Todo lo que se está manejando aquí con el objeto request,
            viene de las interfaces estándar establecidas en PSR-7, 
            buscar en la documentación.
        */

        // Redireccion si la sesion no esta definida por un usuario
        if ( !isset($_SESSION['user']) ) {
            return new redirectResponse($rutasPublicas['signin']);
        }
        $autor = $_SESSION['user']['email'];

        if ($request->getMethod() == 'POST')
        {
            // Validar entrada de datos con librería respect/validation

            // Si la validación dentro del bloque try falla, el validador arroja una exepción la cual
            // es captada y manejada sin detener el flujo de la aplicación
            try {
                // postData sería como $_POST
                $postData = $request->getParsedBody();
                
                $files = $request->getUploadedFiles(); // Desde la super global $_FILES
                $miniatura = $files['miniatura'];

                if ( !Validator::notEmpty()->validate($postData['titulo']) ) {
                    throw new Exception('El título no puede estar vacío');
                }
                if ( !Validator::length(null, 250)->validate($postData['titulo']) ) {
                    throw new Exception('Título demasiado largo');
                }

                if($_FILES['miniatura']['name'] == ''){
                    throw new Exception('Se debe subir una miniatura');
                }

                if($miniatura->getError() == UPLOAD_ERR_OK){
                    // Crea la carpeta si no existe
                    if(!file_exists('../public/uploads')) {
                        mkdir('../public/uploads');
                    }

                    // Mover imagen a uploads
                    $filename = time() . '-' . $miniatura->getClientFilename();
                    $miniatura->moveTo( $CONF['PATH']['UPLOADS'] . '/' . $filename);
                }

                $NuevoPost = new BDPosts();
                $NuevoPost->autor = $autor;
                $NuevoPost->titulo = $postData['titulo'];
                $NuevoPost->miniatura = $filename;
                $NuevoPost->save();

                $mensaje = 'Publicación realizada con éxito!';
                $response = new HtmlResponse($twigVistas->renderizar('nuevoPostRealizado.twig.html', [
                    'mensaje' => $mensaje
                    ]));                
            } catch (Exception $e) {
                $mensaje = $e->getMessage();

                $response = new HtmlResponse($twigVistas->renderizar('nuevoPost.twig.html', [
                    'mensaje' => $mensaje,
                    'autor' => $autor
                ]));
            }
            
            return $response;
        }
        else
        {
            //Renderizar la platilla con Twig
            $response = new HtmlResponse($twigVistas->renderizar('nuevoPost.twig.html', [
                'autor' => $autor
                ]));
            return $response;
        }
    }

}

?>