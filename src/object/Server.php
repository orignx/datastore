<?php

namespace Orignx\Datastore\Object;

class Server extends \Orignx\Datastore\Object
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