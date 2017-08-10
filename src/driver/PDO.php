<?php

namespace orignx\datastore\driver;

use orignx\datastore\exceptions\Driver as DriverException;

/*abstract*/ class PDO extends \orignx\datastore\Driver
{
    private $pdo;
    private $dsn;
    private $connected;
    private $fetchMode;
    private $statement;
    
    public function __construct($name, $config)
    {
        parent::__construct('pdo', $config);
        
        $this->fetchMode = \PDO::FETCH_ASSOC;
        $this->setDriverName($name);
        $this->setDSN($config);
        $this->connect();
    }

    public function connect()
    {
        if ($this->connected !== true) {
            try {
                $this->pdo = new \PDO($this->dsn, $this->config['user'], $this->config['password']);
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
        $this->statement = null;
        $this->connected = false;
    }
    
    public function getPDO()
    {
        return $this->pdo;
    }
    
    public function getDSN()
    {
        return $this->dsn;
    }
    
    public function getFetchMode()
    {
        return $this->fetchMode;
    }   
    
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    public function count()
    {
        return $this->statment->rowCount();
    }
    
    public function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    public function setFetchMode($fetchMode)
    {
        $this->fetchMode = $fetchMode;
    }

    public function query($query, $bindData = [])
    {
        try {
            if (is_array($bindData)) {
                $this->statment = $this->prepare($query, $bindData);
                $this->statment->execute();
            } else {
                $this->statment = $this->pdo->query($query);
            }
        } catch (\PDOException $e) {
            $boundData = json_encode($bindData);
            throw new DriverException("{$e->getMessage()} [$query] [BOUND DATA:$boundData]");
        }
        
        $rows = $this->fetchRows();
        $this->statment->closeCursor();
        return $rows;
    }
    
    private function prepare($query, $bindData) {
        $this->statment = $this->pdo->prepare($query);
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
            $this->statement->bindValue(is_numeric($key) ? $key + 1: $key, $value, $type);
        }
        return $this->statment;
    }

    private function fetchRows() 
    {
        try {
            $rows = $this->statment->fetchAll($this->fetchMode);
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