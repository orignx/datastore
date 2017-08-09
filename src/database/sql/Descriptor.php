<?php

namespace orignx\database\sql;

abstract class Descriptor
{
    protected $driver;
    
    public function __construct($driver) {
        $this->driver = $driver;
    }
    
    abstract protected function getTables($schema, $requestedTables, $includeViews);
    
    abstract protected function getColumns(&$table);
    
    abstract protected function getViews(&$schema);
    
    abstract protected function getPrimaryKey(&$table);
    
    abstract protected function getUniqueKeys(&$table);
    
    abstract protected function getForeignKeys(&$table);
    
    abstract protected function getIndices(&$table);
}