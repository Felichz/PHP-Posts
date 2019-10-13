<?php

// ======================== RUTAS ========================

$CONF['HOST'] = 'http://' . $_SERVER['HTTP_HOST'];

// En $CONF['path'] se guardan las rutas del servidor, no de acceso por los clientes
$CONF['PATH']['ROOT'] = __DIR__;
$CONF['PATH']['UPLOADS'] = $CONF['PATH']['ROOT'] . '/public/uploads';

// La ruta http a la app luego del host, accesible desde los clientes
$CONF['APP_DIR'] = getenv('APP_DIR');

// ======================== BASE DE DATOS ========================
$CONF['DB_HOST'] = getenv('DB_HOST');
$CONF['DB_NAME'] = getenv('DB_NAME');
$CONF['DB_USER'] = getenv('DB_USER');
$CONF['DB_PASS'] = getenv('DB_PASS');

?>