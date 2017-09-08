<?php

namespace orignx\datastore\sql;

use orignx\datastore\Driver as Driver;
use orignx\datastore\object\Schema as Schema;
use orignx\utility\object\Container as Container;

abstract class Descriptor
{
    protected $driver;
    
    protected $tables;
    protected $schemas;
    protected $columns;
    
    public $description;

    public function __construct(Driver $driver)
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
        $this->description;
        
        $schemas = $this->getSchemas();
        $this->schemas = new Container(Schema::class);
        foreach ($schemas as $schema) {
            $tables = $this->getTables($schema['name']);
            $this->schemas[$schema['name']] = $schema;
            $this->schemas[$schema['name']]->setTables($tables);
        }
        var_dump($this->schemas, $this->schemas->count);
        die('dead');
    }
}