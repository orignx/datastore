<?php

abstract class Manipulator
{
    abstract protected function addSchema($name);

    abstract protected function dropSchema($name);

    abstract protected function addTable($params);

    abstract protected function dropTable($params);

    abstract protected function changeTableName($params);

    abstract protected function addColumn($params);

    abstract protected function changeColumnNulls($params);

    abstract protected function changeColumnName($params);

    abstract protected function changeColumnDefault($params);

    abstract protected function dropColumn($params);

    abstract protected function addPrimaryKey($params);

    abstract protected function dropPrimaryKey($params);

    abstract protected function addUniqueKey($params);

    abstract protected function dropUniqueKey($params);

    abstract protected function addAutoPrimaryKey($params);

    abstract protected function dropAutoPrimaryKey($params);

    abstract protected function addForeignKey($params);

    abstract protected function dropForeignKey($params);

    abstract protected function addIndex($params);

    abstract protected function dropIndex($params);

    abstract protected function addView($params);
    
    abstract protected function dropView($params);

    abstract protected function changeViewDefinition($params);

}