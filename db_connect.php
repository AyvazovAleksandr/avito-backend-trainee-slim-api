<?php
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => _DB_DRIVER_,
    'host'      => _DB_SERVER_,
    'database'  => _DB_NAME_,
    'username'  => _DB_USER_,
    'password'  => _DB_PASSWD_,
    'charset'   => _CHARSET_,
    'collation' => _COLLATION_,
    'prefix'    => '',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();