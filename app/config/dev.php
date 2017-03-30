<?php

$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'dbname'   => 'vdm',
    'user'     => 'root',
    'password' => '',
);

// enable the debug mode
$app['debug'] = true;