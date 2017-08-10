<?php

namespace orignx\datastore\object;

class DataCenter extends \orignx\datastore\Object
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