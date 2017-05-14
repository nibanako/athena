<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/../config/providers.php';
require_once __DIR__ . '/../config/routes.php';

$app->run();
