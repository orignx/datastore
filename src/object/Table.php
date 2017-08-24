<?php

namespace orignx\datastore\object;

class Table extends orignx\datastore\DBObject
{
    public function __construct($params)
    {
        parent::__construct('table');
        
    }
    
}