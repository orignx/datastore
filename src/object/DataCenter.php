<?php

namespace Orignx\Datastore\Object;

class DataCenter extends \Orignx\Datastore\Object
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