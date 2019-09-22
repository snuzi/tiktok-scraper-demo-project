<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'host' => getenv('DB_HOST'),
	'driver' => getenv('DB_CONNECTION'),
	'database' => getenv('DB_DATABASE'),
    'username'  => 'root',
    'password'  => getenv('DB_ROOT_PASSWORD'),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();