<?php

namespace orignx\datastore;

class DBObject
{
    private $objectType;
    
    public function __construct($type)
    {
        $this->objectType = $type;
    }
    
    public function objectType()
    {
        $this->objectType;
    }    
}