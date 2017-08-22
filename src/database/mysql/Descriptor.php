<?php

namespace orignx\datastore\database\postgresql;

class Descriptor extends \orignx\datastore\sql\Information
{
    protected function getForeignKeys(&$table)
    {
        return $this->driver->query(sprintf("SELECT
            kcu.constraint_name as name,
            kcu.table_schema as `schema`,
            kcu.table_name as `table`,
            kcu.column_name as `column`,
            kcu.referenced_table_name AS foreign_table,
            kcu.referenced_table_schema AS foreign_schema,
            kcu.referenced_column_name AS foreign_column,
            rc.update_rule as on_update,
            rc.delete_rule as on_delete
           FROM information_schema.table_constraints AS tc
            JOIN information_schema.key_column_usage AS kcu
            ON tc.constraint_name = kcu.constraint_name and tc.table_schema = kcu.table_schema
            JOIN information_schema.referential_constraints AS rc
            ON rc.constraint_name = tc.constraint_name and rc.constraint_schema = tc.table_schema
            WHERE constraint_type = 'FOREIGN KEY'
            AND tc.table_name='%s' AND tc.table_schema='%s' order by kcu.constraint_name, kcu.column_name",
            $table['name'], $table['schema']
        ));
    }

    protected function getIndices(&$table)
    {
        return $this->driver->query(sprintf("SELECT
            table_name,
            column_name as `column`,
            index_name as `name` 
           FROM information_schema.STATISTICS 
            WHERE INDEX_NAME not in (SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE)
            AND table_name = '%s' and table_schema = '%s'
            ORDER BY index_name, column_name",
            $table['name'], $table['schema']
        ));
    }
    
    protected function getSchemas()
    {
        return $this->driver->query("SELECT
            schema_name AS name 
           FROM information_schema.schemata
            WHERE schema_name <> 'information_schema'
            ORDER BYschema_name"
        );
    }
}