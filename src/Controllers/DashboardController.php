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
        if ($app['session']->get('user') == null) {
            $app['session']->getFlashBag()->add('error', 'Debes iniciar sesión antes de acceder');
            return $app->redirect($app['url_generator']->generate('login'));
        }

        $user = new User(
            $app['session']->get('user'),
            $app['db']
        );

        $serverModel = new Server(null, $app['db']);
        $servers = $serverModel->findAllByUser($user);

        return $app['twig']->render('Dashboard/index.html.twig', [
            'user' => $user,
            'servers' => $servers
        ]);
    }
}