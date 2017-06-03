<?php

$app->get('/', 'Athena\Controllers\LoginController::index')->bind('login');
$app->post('/', 'Athena\Controllers\LoginController::signin')->bind('signin');

$app->get('/dashboard', 'Athena\Controllers\DashboardController::index')->bind('dashboard');