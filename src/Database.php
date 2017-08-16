<?php

namespace orignx\datastore;

class Database extends \orignx\datastore\Object
{
    private $driver;
    private $tables;
    private $schemas;
    private $columns;
    
    public function __construct($params)
    {
        parent::__construct('database');
        $name = ucwords($params['name']);
        $reflection = new \ReflectionClass("\\orignx\\datastore\\driver\\{$params['driver']}\\database\\{$name}");
        
        $this->driver = $reflection->newInstance($params['name'], $params['config']);
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