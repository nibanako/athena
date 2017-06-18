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
        if (null === $app['session']->get('user')) {
            return $app['twig']->render('Login/index.html.twig');
        } else {
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }
    }

    public function signin(Request $request, Application $app)
    {
        $userModel = new User(null, $app['db']);
        $user = $userModel->findByUsername($request->get('username'));

        if ($user && $user->verifyPassword($request->get('password'))) {
            $app['session']->set('user', $user->getId());

            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        $app['session']->getFlashBag()->add('error', 'Credenciales incorrectas');

        return $app->redirect($app['url_generator']->generate('login'));
    }

    public function signout(Request $request, Application $app)
    {
        $app['session']->clear();
        return $app->redirect($app['url_generator']->generate('login'));
    }
}