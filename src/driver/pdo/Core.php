<?php

namespace orignx\datastore\driver\pdo;

use orignx\datastore\exceptions\Driver as DriverException;

abstract class Core extends \orignx\datastore\Driver
{
    private $pdo;
    private $dsn;
    private $connected;
    private $fetchMode;
    private $statement;
    
    private $type;
    protected $name;
    protected $config;
    
    private static $transactions = 0;
    
    public function __construct($name, $config)
    {
        $this->type = 'pdo';
        $this->config = $config;
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
    
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
    
    public function columnCount()
    {
        return $this->statement->columnCount();
    }
    
    public function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    public function setFetchMode($fetchMode)
    {
        $this->fetchMode = $fetchMode;
    }
    
    public function beginTransaction()
    {
        if (self::$transactions++ === 0) {
            $this->pdo->beginTransaction();
        }
    }
    
    public function commit()
    {
        if (--self::$transactions === 0) {
            $this->pdo->commit();
        }
    }
    
    public function rollback()
    {
        $this->pdo->rollBack();
        self::$transactions = 0;
    }
    
    public function getPreparedStatement()
    {
        return $this->statement;
    }
    
    public function prepare($query)
    {
        $this->statement = $this->pdo->prepare($query);
    }
    
    public function execute($data)
    {
        foreach($data as $key => $value) {
            $this->statement->bindValue(is_numeric($key) ? $key + 1 : $key, $value, $this->getType($value));
        }
        $this->statement->execute();
    }

    public function quotedQuery($query, $bindData = false)
    {
        return $this->query($this->quoteIdentifiers($query), $bindData);
    }
    
    public function quoteIdentifiers($query)
    {
        return preg_replace_callback('/\"([a-zA-Z\_ ]*)\"/', function($matches) {
            return $this->escape($matches[1]);
        }, $query);
    }
    
    public function query($query, $bindData = [])
    {
        try {
            if (is_array($bindData)) {
                $this->prepare($query);
                $this->statement->execute($bindData);
            } else {
                $this->statement = $this->pdo->query($query);
            }
        } catch (\PDOException $e) {
            $boundData = json_encode($bindData);
            throw new DriverException("{$e->getMessage()} [$query] [BOUND DATA:$boundData]");
        }
        
        $rows = $this->fetchRows();
        $this->statement->closeCursor();
        return $rows;
    }
    
    private function getType($value)
    {
        switch(gettype($value)) {
            case "integer": 
                return \PDO::PARAM_INT;
            case "boolean": 
                return \PDO::PARAM_BOOL;
            default: 
                return \PDO::PARAM_STR;
        }
    }

    private function fetchRows() 
    {
        $statement = $this->statement;
        $rows = $statement->fetchAll($this->fetchMode);
        return $rows;
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