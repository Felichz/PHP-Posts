<?php namespace App\Model;

use Illuminate\Database\Capsule\Manager as Capsule;

class BDConection {

    public function conectar()
    {
        global $CONF;
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $CONF['DB_HOST'],
            'database'  => $CONF['DB_NAME'],
            'username'  => $CONF['DB_USER'],
            'password'  => $CONF['DB_PASS'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        
        $capsule->setAsGlobal();
        
        $capsule->bootEloquent();
    }
}

?>