<?php

namespace orignx\datastore\sql;

class Assertor
{
    private $descriptor;
    
    public function __construct($descriptor)
    {        
        $this->descriptor = $descriptor;
    }
    
    public function doesSchemaExist($details)
    {
        return isset($this->descriptor['schema'][$details]);
    }
    
    public function doesTableExist($details)
    {
        if(is_string($details))
        {
            $details = array(
                'schema' => false,
                'name' => $details
            );
        }
        
        return $details['schema'] == false? 
            isset($this->descriptor['tables'][$details['name']])  : 
            isset($this->descriptor['schema'][$details['schema']]['tables'][$details['name']]);
    }
    
    public function doesColumnExist($details)
    {
        $table = $this->getTableDetails($details['schema'], $details['table']);
        return isset($table['columns'][$details['name']]) ? 
            Parameters::wrap($table['columns'][$details['name']]) : false;
    }
    
    private function doesItemExist($details, $type)
    {
        $table = $this->getTableDetails($details['schema'], $details['table']);
        if(isset($details['columns']))
        {
            return isset($table["flat_$type"][$details['columns'][0]]) ? $table["flat_$type"][$details['columns'][0]] : false;
        }
        else if(isset($details['name']))
        {
            return isset($table[$type][$details['name']]) ? $table[$type][$details['name']] : false;
        }        
    }
    
    public function doesForeignKeyExist($details)
    {
        return $this->doesItemExist($details, 'foreign_keys');
    }
    
    public function doesUniqueKeyExist($details)
    {
        return $this->doesItemExist($details, 'unique_keys');
    }
    
    public function doesPrimaryKeyExist($details)
    {
        return $this->doesItemExist($details, 'primary_key');
    } 
    
    public function doesIndexExist($details)
    {
        return $this->doesItemExist($details, 'indices');
    }
    
    public function doesSequenceExist($details)
    {
        return $this->doesItemExist($details, 'sequences');
    }
    
    public function doesViewExist($details)
    {
        if(is_string($details)) {
            $details = [
                'schema' => false,
                'name' => $details
            ];
        }
        
        // too complex 
        if($details['schema'] == false) {
            return isset($this->descriptor['views'][$details['name']]) ? $this->descriptor['views'][$details['name']]['definition'] : false ;
        } else {
            return (isset($this->descriptor['schema'][$details['schema']]['views'][$details['name']]) ?
                $this->descriptor['schema'][$details['schema']]['views'][$details['name']]['definition'] : false);
        }
    }
    
    private function getTableDetails($schema, $table)
    {
        return $schema === false ? $this->descriptor['tables'][$table] : 
            $this->descriptor['schema'][$schema]['tables'][$table];        
    }    
}
