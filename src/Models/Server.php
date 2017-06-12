<?php

namespace Athena\Models;

use Athena\Models\User;

class Server extends BaseModel
{
    private $id;
    private $name;
    private $ip;
    private $username;
    private $password;
    private $port;
    private $userId;

    public function __construct($id = null, $db)
    {
        parent::__construct($db);

        if ($id != null) {
            $result = $db->fetchAssoc("SELECT * FROM servers WHERE id = $id");
            if (!empty($result)) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->ip = $result['ip'];
                $this->username = $result['username'];
                $this->password = $result['password'];
                $this->port = $result['port'];
                $this->userId = $result['user_id'];
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getUser()
    {
        return new User($this->userId, $this->db);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setUser($user)
    {
        $this->userId = $user->getId();
    }

    public function save()
    {
        $this->db->insert('servers', [
            'name' => $this->name,
            'ip' => $this->ip,
            'username' => $this->username,
            'password' => $this->password,
            'port' => $this->port,
            'user_id' => $this->userId
        ]);
    }

    public function delete()
    {
        $this->db->delete('servers', [
            'id' => $this->id
        ]);
    }

    public function findAllByUser($user)
    {
        $servers = [];

        $result = $this->db->fetchAll('SELECT * FROM servers WHERE user_id = ' . $user->getId());
        foreach ($result as $row) {
            $servers[] = new Server($row['id'], $this->db);
        }

        return $servers;
    }
}