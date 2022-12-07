<?php

namespace Api\Models;

use Api\Core\Database;

class BaseModel
{
    protected $dbConnect;

    protected function __construct()
    {
        $this->dbConnect = Database::getDbConnect();
    }
}