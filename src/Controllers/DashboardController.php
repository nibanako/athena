<?php
namespace Athena\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Athena\Models\User;
use Athena\Models\Server;

class DashboardController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('Dashboard/index.html.twig');
    }
}