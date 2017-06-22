<?php
namespace Athena\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Athena\Models\User;
use Athena\Models\Server;

class ServersController
{
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

    public function delete(Request $request, Application $app)
    {
        $serverId = $request->get('serverId');

        $server = new Server($serverId, $app['db']);
        $server->delete();

        return $app->redirect($app['url_generator']->generate('dashboard'));
    }

    public function ping(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);

        exec('ping -c 3 ' . $server->getIp(), $output);

        $result = strpos($output[5], '100.0%') === false ? 'ok' : 'ko';

        return $app->json(['status' => $result]);
    }

    public function show(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);

        $data = [];
        $conn = ssh2_connect($server->getIp(), $server->getPort() == 0 ? 22 : $server->getPort());
        if (ssh2_auth_password($conn, $server->getUsername(), $server->getPassword())) {
            // Datos generales
            $data = [
                'uptime' => $this->remoteCall($conn, 'uptime | cut -dp -f2 | cut -d, -f1'),
                'so' => $this->remoteCall($conn, 'uname -rs'),
                'cpu' => $this->remoteCall($conn, 'grep \'model name\' /proc/cpuinfo | cut -d: -f2'),
                'ram' => $this->remoteCall($conn, 'grep \'MemTotal\' /proc/meminfo | cut -d: -f2'),
                'hdd' => $this->remoteCall($conn, 'dmesg | grep blocks | cut -d\( -f2 | cut -d/ -f1')
            ];
            // Procesos
            $data['procesos'] = $this->getProcess($conn);
            $data['server'] = $server;

            return $app['twig']->render('Servers/show.html.twig', $data);
        } else {
            // TODO: Devolver un mensaje de error al usuario
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }
    }

    public function getHddUsage(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);
        $conn = ssh2_connect($server->getIp(), $server->getPort() == 0 ? 22 : $server->getPort());

        if (ssh2_auth_password($conn, $server->getUsername(), $server->getPassword())) {
            $remain = explode(' ', $this->remoteCall($conn, 'dmesg | grep blocks | cut -d\( -f2 | cut -d/ -f2'))[0];
            $total = explode(' ', $this->remoteCall($conn, 'dmesg | grep blocks | cut -d\( -f2 | cut -d/ -f1'))[0];

            $result = [
                'used' => number_format($total - $remain, 2),
                'remain' => number_format($remain, 2)
            ];

            return $app->json(['hddUse' => $result]);
        } else {
            return $app->json(['error' => 'No se ha podido establecer la conexión con el servidor.']);
        }
    }

    public function getAvg(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);
        $conn = ssh2_connect($server->getIp(), $server->getPort() == 0 ? 22 : $server->getPort());

        if (ssh2_auth_password($conn, $server->getUsername(), $server->getPassword())) {
            return $app->json([
                'avg' => [
                    'time' => date('H:i:s'),
                    'mark' => number_format($this->remoteCall($conn, "cat /proc/loadavg | cut -d' ' -f1"), 2) * 100
                ]
            ]);
        } else {
            return $app->json(['error' => 'No se ha podido establecer la conexión con el servidor.']);
        }
    }

    public function getRamUsage(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);
        $conn = ssh2_connect($server->getIp(), $server->getPort() == 0 ? 22 : $server->getPort());

        if (ssh2_auth_password($conn, $server->getUsername(), $server->getPassword())) {
            $remain = $this->remoteCall($conn, 'cat /proc/meminfo | grep \'MemAvailable\' | cut -d: -f2 | cut -dk -f1');
            $total = $this->remoteCall($conn, 'cat /proc/meminfo | grep \'MemTotal\' | cut -d: -f2 | cut -dk -f1');

            $used = $total - $remain;

            $percent = ($used * 100) / $total;

            return $app->json([
                'ram' => [
                    'time' => date('H:i:s'),
                    'mark' => number_format($percent, 2)
                ]
            ]);
        } else {
            return $app->json(['error' => 'No se ha podido establecer la conexión con el servidor.']);
        }
    }

    public function getProcesses(Request $request, Application $app)
    {
        $server = new Server($request->get('id'), $app['db']);
        $conn = ssh2_connect($server->getIp(), $server->getPort() == 0 ? 22 : $server->getPort());

        if (ssh2_auth_password($conn, $server->getUsername(), $server->getPassword())) {
            $processes = $this->getProcess($conn);

            return $app->json($processes);
        } else {
            return $app->json(['error' => 'No se ha podido establecer la conexión con el servidor.']);
        }
    }

    private function remoteCall($conn, $command)
    {
        $stream = ssh2_exec($conn, $command);
        stream_set_blocking($stream, true);
        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);

        return trim(stream_get_contents($stream_out));
    }
    
    private function getProcess($conn)
    {
        $stream = ssh2_exec($conn, 'ps -au');
        stream_set_blocking($stream, true);

        $procesos = [];
        $x = 0;
        while ($line = fgets($stream)) {
            $proceso = [];
            preg_match_all('/[^\s"\']+|"([^"]*)"|\'([^\']*)\'/', $line, $data);

            $data = $data[0];

            if ($x > 0) {
                $proceso = [
                    'user' => array_shift($data),
                    'pid' => array_shift($data),
                    'cpu' => array_shift($data),
                    'mem' => array_shift($data)
                ];
                $data = array_slice($data, 5);
                $proceso['time'] = array_shift($data);
                $proceso['command'] = implode(' ', $data);
            }

            $procesos[] = $proceso;
            $x++;
        }
        
        return array_slice($procesos, 1);
    }
}