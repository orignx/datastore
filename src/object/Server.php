<?php

namespace Datastore\Object;

class Server extends \Datastore\Object
{
    private $port;
    private $type;
    private $host;

    public function __construct($params)
    {
        parent::__construct('server');
        
        $this->port = $params['port'];
        $this->type = $params['type'];
        $this->host = $params['host'];
    }

    public function addDatabase($database)
    {
        
    }
}