<?php

namespace orignx\datastore;

class Database extends \orignx\datastore\DBObject
{
    public $driver;
    public $descriptor;
    
    public function __construct($params)
    {
        parent::__construct('database');
        $name = ucwords($params['name']);
        $descriptor = new \ReflectionClass("\\orignx\\datastore\\database\\{$params['name']}\\Descriptor");
        $driver = new \ReflectionClass("\\orignx\\datastore\\driver\\{$params['driver']}\\database\\{$name}");
        
        $this->driver = $driver->newInstance($params['name'], $params['config']);
        $this->descriptor = $descriptor->newInstance($this->driver);
    }
    
    public function getDriver()
    {
        return $this->driver;
    }

    public function hasSchema()
    {
    }

    public function addSchema($schema)
    {
        
    }
    
    public function addTable($table)
    {
        
    }
    
}