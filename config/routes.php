<?php

$app->get('/', 'Athena\Controllers\LoginController::index')->bind('login');
$app->post('/', 'Athena\Controllers\LoginController::signin')->bind('signin');

$app->get('/dashboard', 'Athena\Controllers\DashboardController::index')->bind('dashboard');

$app->post('/servers/save', 'Athena\Controllers\ServersController::save')->bind('saveServer');
$app->post('/servers/delete', 'Athena\Controllers\ServersController::delete')->bind('deleteServer');

$app->get('/servers/show/{id}', 'Athena\Controllers\ServersController::show')->bind('showServer');

$app->get('/servers/ping/{id}', 'Athena\Controllers\ServersController::ping')->bind('pingServer');