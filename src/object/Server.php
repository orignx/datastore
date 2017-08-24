<?php

namespace orignx\datastore\object;

class Server extends \orignx\datastore\DBObject
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