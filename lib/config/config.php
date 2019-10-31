<?php

// Acceso a la carpeta raiz de la app desde el server
$APP_ROOT = dirname(__DIR__, 2);

return [
    // Rutas
    // Acceso HTTP
    'HOST' => 'http://' . $_SERVER['HTTP_HOST'],
    'APP_DIR' => getenv('APP_DIR'),
    // Especifica si se deben mostrar errores o no
    'DEBUG' => getenv('DEBUG') == 'true' ? true : false,
    // Acceso desde el server
    'PATH' => [
        'ROOT' => $APP_ROOT,
        'UPLOADS' => $APP_ROOT . '/public/uploads',
        'UTILS' => $APP_ROOT . '/lib/Utils'
    ],

    // Base de Datos
    'DB_HOST' => getenv('DB_HOST'),
    'DB_NAME' => getenv('DB_NAME'),
    'DB_USER' => getenv('DB_USER'),
    'DB_PASS' => getenv('DB_PASS')
];

?>