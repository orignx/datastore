<?php

namespace orignx\datastore\sql;

abstract class Descriptor
{
    protected $driver;
    
    public function __construct($driver)
    {
        $this->driver = $driver;
    }
    
    abstract protected function getSchemas();
    
    abstract protected function getTables($schema, $includeViews = false);
    
    abstract protected function getColumns(&$table);
    
    abstract protected function getViews(&$schema);
    
    abstract protected function getPrimaryKey(&$table);
    
    abstract protected function getUniqueKeys(&$table);
    
    abstract protected function getForeignKeys(&$table);
    
    abstract protected function getIndices(&$table);
    
    public function describe() 
    {
    }
}