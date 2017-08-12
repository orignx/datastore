<?php

namespace orignx\datastore\sql;

abstract class Information extends \orignx\datastore\sql\Descriptor
{
    protected function getTables($schema, $includeViews = false)
    {
        $condition = "table_type = ?";
        $bind = [$schema, 'BASE TABLE'];
        
        if ($includeViews) {
            $condition .= " or table_type = ?";
            array_push($bind, 'VIEW');
        }
        
        $this->driver->quotedQuery('SELECT
            "table_name" as "name",
            "table_type" as "type",
            "is_insertable_into" as "insertable",
            "is_typed" as "typed"
           FROM "information_schema"."tables"
            WHERE table_schema = ? and ' . $condition . '
            ORDER BY "table_name"',
            $bind
        );
    }
    
    protected function getColumns(&$table)
    {
        return $this->driver->quotedQuery('SELECT
            "column_name" as "name",
            "data_type" as "type",
            "udt_name" as "class",
            "is_nullable" as "nulls",
            "column_default" as "default",
            "character_maximum_length" as "length",
            "is_updatable" as "updatable",
            "ordinal_position" as "position"
           FROM "information_schema"."columns"
            WHERE "table_name" = ? and "table_schema" = ?
            ORDER BY "column_name"',
            [$table['name'], $table['schema']]
        );
    }
    
    protected function getViews(&$schema)
    {
        return $this->driver->quotedQuery('SELECT
            "table_name" as "name",
            "view_definition" as "definition",
            "is_insertable_into" as "insertable",
            "is_updatable" as "updatable"
           FROM "information_schema"."views"
            WHERE "table_schema" = ?
            ORDER BY "table_name"',
            [$schema]
        );
    }
    
    protected function getPrimaryKey(&$table)
    {
        return $this->getConstraints($table, 'PRIMARY KEY');
    }

    protected function getUniqueKeys(&$table)
    {
        return $this->getConstraints($table, 'UNIQUE');
    }
    
}