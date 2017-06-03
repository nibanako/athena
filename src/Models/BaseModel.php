<?php

namespace Athena\Models;

class BaseModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}