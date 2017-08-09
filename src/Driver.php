<?php

namespace orignx\datastore;

abstract class Driver
{
    private $type;
    private $name;
    protected $config;
    
    public function __construct($type, $config = null)
    {
        $this->type = $type;
        $this->config = $config;
    }
    
    public function __destruct() {
        $this->disconnect();
    }
    
    public function open() {
        $this->connect();
    }
    
    public function close() {
        $this->disconnect();
    }
    
    protected function setDriverName()
    {
        return $this->type;
    }
    
    protected function getDriverName()
    {
        return $this->name;
    }
    
    public function getDriverType()
    {
        return $this->type;
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    abstract public function query();
   
//    abstract public function escape();
    
    abstract public function connect();
    
    abstract public function disconnect();

}

