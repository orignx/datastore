<?php

namespace orignx\datastore\driver\pdo\database;

class Pgsql extends \orignx\datastore\driver\pdo\Core
{
    protected function getDriverName() 
    {
        return 'pgsql';
    }
    
    public function escape($identifier)
    {
        return "\"$identifier\"";
    }    
    
    public function getLastInsertId() 
    {
        $lastval = $this->query("SELECT LASTVAL() as last");
        return $lastval[0]["last"];        
    }    
}
