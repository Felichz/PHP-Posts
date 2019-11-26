<?php

require_once 'vendor/autoload.php';

use App\Conf\Conf;

$CONF = Conf::getConf();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => $CONF['DB_HOST'],
            'name' => $CONF['DB_NAME'],
            'user' => $CONF['DB_USER'],
            'pass' => $CONF['DB_PASS'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => $CONF['DB_HOST'],
            'name' => $CONF['DB_NAME'],
            'user' => $CONF['DB_USER'],
            'pass' => $CONF['DB_PASS'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];