<?php

namespace orignx\datastore\driver\pdo\database;

class Mysql extends \orignx\datastore\driver\pdo\Core
{   
    protected function getDriverName()
    {
        return 'mysql';
    }

    public function escape($identifier)
    {
        return "`$identifier`";
    }
}