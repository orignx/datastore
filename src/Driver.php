<?php

namespace orignx\datastore;

abstract class Driver
{
    private $type;
    protected $name;
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
    
    protected function setDriverName($name)
    {
        $this->name = $name;
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
   
    abstract public function escape();
    
    abstract public function connect();
    
    abstract public function disconnect();
    
    abstract public function query($query, $bindData);
}

