<?php
namespace Athena\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Athena\Models\User;
use Athena\Models\Server;

class ServersController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('Servers/index.html.twig');
    }

    public function save(Request $request, Application $app)
    {
        $user = new User(
            $app['session']->get('user'),
            $app['db']
        );

        $serverModel = new Server(null, $app['db']);
        $serverModel->setName($request->get('name'));
        $serverModel->setIp($request->get('ip'));
        $serverModel->setPort($request->get('port'));
        $serverModel->setUsername($request->get('username'));
        $serverModel->setPassword($request->get('password'));
        $serverModel->setUser($user);

        $serverModel->save();

        return $app->redirect($app['url_generator']->generate('dashboard'));
    }

    public function ping(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);

        exec('ping -c 3 ' . $server->getIp(), $output);

        $result = strpos($output[5], '100.0%') === false ? 'ok' : 'ko';

        return $app->json(['status' => $result]);
    }
}