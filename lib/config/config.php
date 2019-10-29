<?php

return [
    // Rutas
    // Acceso HTTP
    'HOST' => 'http://' . $_SERVER['HTTP_HOST'],
    'APP_DIR' => getenv('APP_DIR'),
    // Acceso desde el server
    'PATH' => [
        'ROOT' => dirname(__DIR__, 2),
        'UPLOADS' => dirname(__DIR__, 2) . '/public/uploads'
    ],

    // Base de Datos
    'DB_HOST' => getenv('DB_HOST'),
    'DB_NAME' => getenv('DB_NAME'),
    'DB_USER' => getenv('DB_USER'),
    'DB_PASS' => getenv('DB_PASS')
];

?>