<?php namespace App\Model;

use App\Conf\Conf;
use Illuminate\Database\Capsule\Manager as Capsule;

// Esta clase no cuenta como Model de Eloquent, es el Capsule Manager
class BDConection {

    public function conectar()
    {
        $CONF = Conf::getConf();
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