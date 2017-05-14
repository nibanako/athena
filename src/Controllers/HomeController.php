<?php
namespace Athena\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('Home/index.html.twig');
    }
}