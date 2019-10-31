<?php namespace App\Controller\Container;

use App\Model\BDUsers;
use Exception;
use \Respect\Validation\Validator;

class ValidationController
{

    public function validarSignup( $postData )
    {
        $email = $postData['email'];
        $password = $postData['password'];

        $BDUsers = new BDUsers();

        try
        {
            $error = false;
            $this->errorMessage = NULL;

            // Validar email
            if (!Validator::email()->validate($email)) {
                throw new Exception('Email inválido');
            }

            // Validar contraseña por pasos para poder dar un mensaje más específico
            if (!Validator::alnum()->noWhitespace()->validate($password)) {
                throw new Exception('La contraseña solo admite carácteres alfanuméricos y sin espacios en blanco');
            }
            if (!Validator::length(5, null)->Validate($password)) {
                throw new Exception('La contraseña debe contener al menos 5 caracteres');
            }
            if (!Validator::length(null, 250)->Validate($password)) {
                throw new Exception('Contraseña demasiado larga');
            }

            // Y por último antes de procesar el registro,
            // verificar que el email aún no esté registrado
            if ($BDUsers->usuarioRegistrado($email)) {
                throw new Exception('Email ya registrado');
            }
        }
        catch ( Exception $e )
        {
            $error = true;
            $this->errorMessage = $e->getMessage();
        }

        return $error == false ? true : false;
    }

    public function validarSignin( $postData )
    {
        $email = $postData['email'];
        $password = $postData['password'];

        $BDUsers = new BDUsers();
        try
        {
            $error = false;
            $this->errorMessage = NULL;

            // Validar email
            if (!Validator::email()->validate($email)) {
                throw new Exception('Email inválido');
            }

            // verificar que el email esté registrado
            if (!$BDUsers->usuarioRegistrado($email)) {
                throw new Exception('No existe un usuario con ese email');
            }

            if (!$BDUsers->verificarPassword($email, $password)) {
                throw new Exception('Datos incorrectos');
            }
        }
        catch ( Exception $e )
        {
            $error = true;
            $this->errorMessage = $e->getMessage();
        }

        return $error == false ? true : false;
    }

    public function validarNuevoPost ( $postData, $miniatura ) 
    {
        try
        {
            $error = false;
            $this->errorMessage = NULL;

            // Titulo
            if ( !Validator::notEmpty()->validate($postData['titulo']) ) {
                throw new Exception('El título no puede estar vacío');
            }
            if ( !Validator::length(null, 250)->validate($postData['titulo']) ) {
                throw new Exception('Título demasiado largo');
            }

            // Miniatura
            if( $miniatura->getError() == 4 ){
                throw new Exception('Se debe subir una miniatura');
            }

            if($miniatura->getError() != UPLOAD_ERR_OK){
                throw new Exception('Error al guardar miniatura');
            }
        }
        catch ( Exception $e )
        {
            $error = true;
            $this->errorMessage = $e->getMessage();
        }

        return $error == false ? true : false;
    }
}

?>