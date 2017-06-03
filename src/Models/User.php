<?php

namespace Athena\Models;

class User extends BaseModel
{
    private $id;
    private $username;
    private $password;

    public function __construct($id = null, $db)
    {
        parent::__construct($db);

        if ($id != null) {
            $result = $db->fetchAssoc("SELECT * FROM users WHERE id = $id");
            if (!empty($result)) {
                $this->id = $result['id'];
                $this->username = $result['username'];
                $this->password = $result['password'];
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function save()
    {
        $this->db->insert('users', [
            'username' => $this->username,
            'password' => password_hash($this->password, PASSWORD_DEFAULT)
        ]);
    }
}