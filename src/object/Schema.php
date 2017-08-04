<?php

namespace Datastore\Object;

class Schema extends \Datastore\Object
{
    private $tables;

    public function __construct()
    {
        parent::__construct('schema');
        
    }
    
    public function addTable(\Datastore\Object\Table $table)
    {
        
    }
}