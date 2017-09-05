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
        
        return $this->driver->quotedQuery('SELECT
            "table_name" AS "name",
            "table_type" AS "type",
            "is_insertable_into" AS "insertable",
            "is_typed" AS "typed"
           FROM "information_schema"."tables"
            WHERE table_schema = ? AND ' . $condition . '
            ORDER BY "table_name"',
            $bind
        );
    }
    
    protected function getColumns(&$table)
    {
        return $this->driver->quotedQuery('SELECT
            "column_name" AS "name",
            "data_type" AS "type",
            "udt_name" AS "class",
            "is_nullable" AS "nulls",
            "column_default" AS "default",
            "character_maximum_length" AS "length",
            "is_updatable" AS "updatable",
            "ordinal_position" AS "position"
           FROM "information_schema"."columns"
            WHERE "table_name" = ? AND "table_schema" = ?
            ORDER BY "column_name"',
            [$table['name'], $table['schema']]
        );
    }
    
    protected function getViews(&$schema)
    {
        return $this->driver->quotedQuery('SELECT
            "table_name" AS "name",
            "view_definition" AS "definition",
            "is_insertable_into" AS "insertable",
            "is_updatable" AS "updatable"
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
        $unique = [];
        foreach ($this->getConstraints($table, 'UNIQUE') AS $constraint) {
            $unique[$constraint['name']][] = $constraint['column'];
        }
        
        return $unique;
    }
    
    private function getConstraints($table, $type)
    {
        return $this->driver->quotedQuery('SELECT
            "tc"."constraint_name" AS "name",
            "column_name" AS "column"
           FROM "information_schema"."table_constraints" "tc"
            JOIN "information_schema"."key_column_usage" "kcu" ON
               "kcu"."table_name" = "tc"."table_name" AND
               "kcu"."constraint_name" = "tc"."constraint_name" AND
               "kcu"."constraint_schema" = "tc"."table_schema"
            WHERE "tc"."table_name" = ? AND tc.table_schema= ? AND constraint_type = ? 
            ORDER BY "tc"."constraint_name", "column_name"',
            [$table['name'], $table['schema'], $type]
        );
    }    
}