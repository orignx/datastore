<?php

namespace orignx\datastore\driver;

use orignx\datastore\exceptions\Driver as DriverException;

/*abstract*/ class PDO extends \orignx\datastore\Driver
{
    private $pdo;
    private $dsn;
    private $connected;
    
    public function __construct($name, $config)
    {
        parent::__construct('pdo', $config);
        $this->setDriverName($name);
        $this->setDSN($config);
        $this->connect();
    }

    public function connect()
    {
        if ($this->connected !== true) {
            try {
                $this->pdo = new \PDO($this->dsn, $this->config['user'], $this->config['password']);
                $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
                $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                $this->connected = true;
            } catch (\PDOException $e) {
                throw new DriverException("PDO connection failed: {$e->getMessage()}", $e);
            }
        }
    }
    
    public function disconnect()
    {
        $this->pdo = null;
        $this->connected = false;
//        $this->pdo = new NullConnection();
    }
    
    public function getPDO()
    {
        return $this->pdo;
    }
    
    public function getDSN()
    {
        return $this->dsn;
    }
    
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    public function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    public function query($query, $bindData = [])
    {
        try {
            if (is_array($bindData)) {
                $statement = $this->prepare($query, $bindData);
                $statement->execute();
            } else {
                $statement = $this->pdo->query($query);
            }
        } catch (\PDOException $e) {
            $boundData = json_encode($bindData);
            throw new DriverException("{$e->getMessage()} [$query] [BOUND DATA:$boundData]");
        }
        
        $rows = $this->fetchRows($statement);
        $statement->closeCursor();
        return $rows;
    }
    
    private function prepare($query, $bindData) {
        $statement = $this->pdo->prepare($query);
        foreach($bindData as $key => $value) {
            switch(gettype($value)) {
                case "integer": 
                    $type = \PDO::PARAM_INT;
                    break;
                case "boolean": 
                    $type = \PDO::PARAM_BOOL;
                    break;
                default: 
                    $type = \PDO::PARAM_STR;
                    break;
            }
            $statement->bindValue(is_numeric($key) ? $key + 1: $key, $value, $type);
        }
        return $statement;
    }

    private function fetchRows($statement) 
    {
        try {
            $rows = $statement->fetchAll();
            return $rows;
        } catch (\PDOException $e) {
            
        }
    }
    
    private function setDSN($config)
    {
        $dsn = [];
        unset($config['driver']);
        foreach ($config as $key => $value){
             if ($value == '') {
                continue;
            } else {
                $dsn[] = "$key=$value";
            }
        }
        
        $this->dsn = "{$this->name}:" . implode(';', $dsn);
    }
}