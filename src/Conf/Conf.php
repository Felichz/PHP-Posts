<?php namespace App\Conf;

use Dotenv\Dotenv;

// Clase Singleton

class Conf
{
    private static $instance = NULL;

    private function __construct()
    {
        //// Cargar variables de entorno ////
        $dotenv = Dotenv::create( dirname(dirname(__DIR__)) ); // Debe apuntar a la carpeta raiz
        $dotenv->load();

        // Acceso a la carpeta raiz de la app desde el server
        $APP_ROOT = dirname(__DIR__, 2);
        
        $PROTOCOL = getenv('APP_SSL') == 'true' ? 'https' : 'http';

        $this->CONF = [
            // Rutas
            // Acceso HTTP
            'HOST' => isset( $_SERVER['HTTP_HOST'] ) ? $PROTOCOL . '://' . $_SERVER['HTTP_HOST'] : false,
            'APP_URI' => getenv('APP_URI'),
            // Entorno de la app
            'APP_ENV' => getenv('APP_ENV'),
            // Especifica si se deben mostrar errores o no
            'DEBUG' => getenv('APP_DEBUG') === 'true' ? true : false,
            // Acceso desde el server
            'PATH' => [
                'ROOT' => $APP_ROOT,
                'LOG' => $APP_ROOT . '/log',
                'BIN' => $APP_ROOT . '/bin',
                'UPLOADS' => $APP_ROOT . '/public/uploads',
                'UTILS' => $APP_ROOT . '/src/Utils'
            ],
        
            // Email
            'EMAIL' => [
                'CONTACT_INBOX' => getenv('CONTACT_INBOX'),

                'SMTP' => [
                    'HOST' => getenv('SMTP_HOST'),
                    'PORT' => getenv('SMTP_PORT'),
                    'USER' => getenv('SMTP_USER'),
                    'PASS' => getenv('SMTP_PASS')
                ]
            ],

            // Base de Datos
            'DB_HOST' => getenv('DB_HOST'),
            'DB_NAME' => getenv('DB_NAME'),
            'DB_USER' => getenv('DB_USER'),
            'DB_PASS' => getenv('DB_PASS')
        ];
    }

    static function getConf()
    {
        if ( self::$instance === NULL )
        {
            self::$instance = new self;
        }

        return self::$instance->CONF;
    }
}