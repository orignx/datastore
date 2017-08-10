<?php
namespace orignx\datastore\exceptions;

class Driver extends \Exception
{
    private $exception;
    
    public function __construct($message, $exception = null) 
    {
        parent::__construct($message);
        $this->exception = $exception;
    }
}
