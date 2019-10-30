<?php namespace App\Controller;

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

}

?>