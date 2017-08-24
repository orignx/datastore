<?php

namespace orignx\datastore\object;

class Schema extends \orignx\datastore\DBObject
{
    private $tables;

    public function __construct()
    {
        parent::__construct('schema');
        
    }
    
    public function addTable(\orignx\datastore\object\Table $table)
    {
        
    }
}