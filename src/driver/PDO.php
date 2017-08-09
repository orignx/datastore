<?php

namespace Datastore\Database\Driver;

/*abstract*/ class PDO extends \Datastore\Driver
{
    private $pdo;
    
    public function __construct($config)
    {
        parent::__construct('pdo', $config);
    }

    public function connect()
    {
        try {
            $this->pdo = new \PDO(
                $this->getDriverName() . ":" . $this->expand($this->config), $this->config['user_name'], $this->config['password']
            );
            $this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            
        }
    }
    
    public function disconnect()
    {
        $this->pdo = null;
        $this->pdo = new NullConnection();
    }
    
    private function fetchRows($statement) 
    {
        try {
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $rows;
        } catch (\PDOException $e) {
            
        }
    }
}