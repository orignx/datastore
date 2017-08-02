<?php

namespace Datastore\Object;

class DataCenter extends \Datastore\Object
{    
    private $servers;
    
    public function __construct()
    {
        parent::__construct('datacenter');
    }
       
    public function addServer($server)
    {
        
    }
}