<?php

$app->get('/', 'Athena\Controllers\LoginController::index')->bind('login');
$app->post('/', 'Athena\Controllers\LoginController::signin')->bind('signin');
$app->get('/signout', 'Athena\Controllers\LoginController::signout')->bind('signout');

$app->get('/dashboard', 'Athena\Controllers\DashboardController::index')->bind('dashboard');

$app->post('/servers/save', 'Athena\Controllers\ServersController::save')->bind('saveServer');
$app->post('/servers/delete', 'Athena\Controllers\ServersController::delete')->bind('deleteServer');

$app->get('/servers/show/{id}', 'Athena\Controllers\ServersController::show')->bind('showServer');

$app->get('/servers/ping/{id}', 'Athena\Controllers\ServersController::ping')->bind('pingServer');
$app->get('/servers/hddUsage/{id}', 'Athena\Controllers\ServersController::getHddUsage')->bind('getHddUsage');
$app->get('/servers/avg/{id}', 'Athena\Controllers\ServersController::getAvg')->bind('getAvg');
$app->get('/servers/ramUsage/{id}', 'Athena\Controllers\ServersController::getRamUsage')->bind('getRamUsage');