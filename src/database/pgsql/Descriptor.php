<?php

namespace orignx\datastore\database\pgsql;

class Descriptor extends \orignx\datastore\sql\Information
{
    protected function getIndices(&$table)
    {
        return $this->driver->query(sprintf("SELECT
            t.relname AS table_name,
            i.relname AS name,
            a.attname AS column
           FROM
            pg_class t,
            pg_class i,
            pg_index ix,
            pg_attribute a,
            pg_namespace n
            WHERE t.oid = ix.indrelid
            AND i.oid = ix.indexrelid
            AND a.attrelid = t.oid
            AND a.attnum = ANY(ix.indkey)
            AND t.relkind = 'r'
            AND t.relname = '%s'
            AND n.nspname = '%s'
            AND i.relnamespace = n.oid
            AND indisunique != 't'
            AND indisprimary != 't'
            ORDER BY i.relname, a.attname", 
            $table['name'], $table['schema']
        ));        
    }
    
    protected function getForeignKeys(&$table)
    {
        return $this->driver->query(
            "SELECT DISTINCT
                kcu.constraint_name AS name,
                kcu.table_schema AS schema,
                kcu.table_name AS table, 
                kcu.column_name AS column, 
                ccu.table_name AS foreign_table,
                ccu.table_schema AS foreign_schema,
                ccu.column_name AS foreign_column,
                rc.update_rule AS on_update,
                rc.delete_rule AS on_delete
               FROM  information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name AND tc.table_schema = kcu.table_schema AND tc.table_name = kcu.table_name 
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name AND ccu.constraint_schema = tc.table_schema
                JOIN information_schema.referential_constraints AS rc
                  ON rc.constraint_name = tc.constraint_name AND rc.constraint_schema = tc.table_schema
                WHERE constraint_type = 'FOREIGN KEY' 
                AND tc.table_name = :name AND tc.table_schema = :schema
                AND kcu.table_name = :name AND kcu.table_schema = :schema
                order by kcu.constraint_name, kcu.column_name",
            ['name' => $table['name'], 'schema' => $table['schema']]
        );
    }
    
    protected function getSchemas()
    {
        return $this->driver->query("SELECT
            schema_name AS name
           FROM information_schema.schemata 
            WHERE schema_name NOT LIKE 'pg_temp%' AND 
            schema_name NOT LIKE 'pg_toast%' AND 
            schema_name NOT IN ('pg_catalog', 'information_schema')
            ORDER BY schema_name"
        );
    }
}