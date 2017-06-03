<?php

$app->register(new WhoopsSilex\WhoopsServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../src/Views',
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'dbname' => 'athena_db',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
]);