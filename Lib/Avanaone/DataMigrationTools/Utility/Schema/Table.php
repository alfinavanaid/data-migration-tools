<?php

namespace Lib\Avanaone\DataMigrationTools\Utility\Schema;

use Lib\Avanaone\DataMigrationTools\CustomRelationField\CustomRelationField;
use Lib\Avanaone\DataMigrationTools\DataSource\DataSourceObject;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\Relations;

class Table
{

    public $table_name;
    public $column;
    public $relations_child;
    public $relations_parent;
    public $table_not_found;
    public $extract_layer;
    public $primary_key;

    public $new_parent_table_name;

    function __construct($table = null)
    {
        $this->table_name = (isset($table->table_name)) ? $table->table_name : null;
        $this->column = (isset($table->column)) ? $table->column : [];
        $this->relations_child = (isset($table->relations_child)) ? $table->relations_child : [];
        $this->relations_parent = (isset($table->relations_parent)) ? $table->relations_parent : [];
        $this->primary_key = (isset($table->primary_key)) ? $table->primary_key : null;
        $this->new_parent_table_name = null;
        $this->extract_layer = null;
    }

    function setName($table_name)
    {
        $this->table_name = $table_name;
    }

    function setNewColumn($column_name)
    {
        array_push($this->column, $column_name);
    }

    function analyzeRelationship($column_name, DataSourceObject $data_source_object)
    {
        if (substr($column_name, -3) === '_id') {
            $reference_table_name = substr($column_name, 0, -3);
            if ($reference_table_name != $this->table_name) {

                /* 
                Search the reference table on the existing table list
                */

                $CustomRelationField = $data_source_object->getCustomRelationsFields();

                if (!array_search($reference_table_name, $data_source_object->getTableList())) {

                    /* 
                    Table not found on the list, we could check on the custom relations field
                    */

                    if ($CustomRelationField->checkTableIgnored($reference_table_name)) {
                        $reference_table_name = $CustomRelationField->getAlias();
                    } elseif ($CustomRelationField->checkTableIgnored($reference_table_name, $column_name, $this->table_name)) {
                        return;
                    } else {
                        $this->table_not_found = [
                            'table' => $reference_table_name,
                            'foreign_column' => $column_name,
                            'foreign_table' => $this->table_name
                        ];
                    }
                }

                if ($CustomRelationField->checkTableColumnIgnored($this->table_name, $column_name)) {
                    return;
                }

                if ($CustomRelationField->checkTableIgnored($reference_table_name)) {
                    return;
                }

                if (!$this->table_not_found) {;
                    if ($this->table_name != $reference_table_name) {
                        array_push($this->relations_parent, Relations::stringWriteRelationship($this->table_name, $column_name, $reference_table_name, $column_name));
                        $this->new_parent_table_name = $reference_table_name;
                    }
                }
            } else {
                $this->primary_key = $column_name;
            }
        }
    }

    function getNewParentTable()
    {
        return $this->new_parent_table_name;
    }

    function getTableNotFound()
    {
        return $this->table_not_found;
    }

    function appendChild($reference_table_name, $column_name)
    {
        array_push($this->relations_child, ($this->table_name . '.' . $column_name . ' = ' . $reference_table_name . '.' . $column_name));
    }

    function get()
    {
        return $this;
    }
}
