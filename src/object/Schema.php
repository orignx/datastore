<?php

namespace Orignx\Datastore\Object;

class Schema extends \Orignx\Datastore\Object
{
    private $tables;

    public function __construct()
    {
        parent::__construct('schema');
        
    }
    
    public function addTable(\Orignx\Datastore\Object\Table $table)
    {
        
    }
}