<?php

namespace Orignx\Datastore\Driver;

/*abstract*/ class PDO extends \Orignx\Datastore\Driver
{
    private $pdo;
    private $connected;
    private $connection;
    
    public function __construct($name, $config)
    {
        parent::__construct('pdo', $config);
        $this->setDriverName($name);
        $this->setConnection();
        $this->connect();
    }

    public function connect()
    {
        if ($this->connected === false) {
            try {
                $this->pdo = new \PDO($this->connection, $this->config['user'], $this->config['password']);
                $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
                $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                $this->connected = true;
            } catch (\PDOException $e) {

            }
        }
    }
    
    public function disconnect()
    {
        $this->pdo = null;
        $this->connected = false;
        $this->pdo = new NullConnection();
    }
    
    public function getPDO()
    {
        return $this->pdo;
    }
    
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    public function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    public function query()
    {
        ;
    }

    private function fetchRows($statement) 
    {
        try {
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $rows;
        } catch (\PDOException $e) {
            
        }
    }
    
    private function setConnection()
    {
        $config = $this->config();
        $config['driver'] = $this->name;
        
        $connection = [];
        foreach ($config as $key => $value){
             if ($value == '') {
                continue;
            } else {
                $connection[] = "$key=$value";
            }
        }
        
        $this->connection = implode(';', $connection);
    }
}