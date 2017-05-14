<?php

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../src/Views',
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => 'pdo_mysql',
    ],
]);