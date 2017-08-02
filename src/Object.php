<?php

namespace Datastore;

class Object
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