<?php

namespace orignx\datastore\object;

class Schema extends \orignx\datastore\Object
{
    private $tables;

    public function __construct()
    {
        parent::__construct('schema');
        
    }
    
    public function addTable(\orignx\datastore\Object\Table $table)
    {
        
    }
}