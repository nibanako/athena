<?php
namespace Athena\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Athena\Models\User;
use Athena\Models\Server;

class LoginController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('Login/index.html.twig');
    }

    public function signin(Request $request, Application $app)
    {
        $userModel = new User(null, $app['db']);
        $user = $userModel->findByUsername($request->get('username'));

        if ($user && $user->verifyPassword($request->get('password'))) {
            $app['session']->set('user', ['username' => $user->getUsername()]);

            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        $app['session']->getFlashBag()->add('error', 'Credenciales incorrectas');

        return $app->redirect($app['url_generator']->generate('login'));
    }
}