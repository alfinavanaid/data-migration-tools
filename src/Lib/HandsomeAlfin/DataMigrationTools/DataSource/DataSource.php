<?php

namespace Lib\HandsomeAlfin\DataMigrationTools\DataSource;

use Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table;
use Lib\HandsomeAlfin\DataMigrationTools\DataSource\ResultAnalysis;
use Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table\DDLSequenceLayer;

class DataSource
{


    private $data_source;
    private $data_source_json;
    private $table_list;
    private $table_not_found;

    private $custom_relations_fields;

    private $active_table = '';
    private $result_analysis;

    function __construct($data_source, $custom_relations_fields)
    {
        $this->data_source = json_decode($data_source);
        $this->data_source_json = [];
        $this->table_not_found = [];
        $this->custom_relations_fields = json_decode($custom_relations_fields);
    }

    function scanTable()
    {
        $this->table_list = array_values(array_unique(array_column($this->data_source, 0)));
        if (count($this->data_source) > 0 && $this->data_source <> null && !empty($this->data_source)) {
            foreach ($this->data_source as $data_source) {
                $this->setTableAndColumn($data_source[0], $data_source[1]);
            }
        }
        // $this->data_source_json = (new DDLSequenceLayer($this->data_source_json))->execute();
        $this->result_analysis = (new ResultAnalysis($this->data_source_json))->getReports();
    }

    private function setTableAndColumn($table_name, $column_name)
    {
        if (!$this->findTable($table_name)) {
            $Table = new Table();
            $Table->setTableName($table_name);
        } else {
            $Table = new Table($this->data_source_json[$table_name]);
        }

        $Table->setNewColumn($column_name);
        $Table->analyzeRelationship($column_name, $this->table_list, $this->custom_relations_fields);

        if ($Table->getNewParentTable() != null) {

            $table_name_parent = $Table->getNewParentTable();
            if (!$this->findTable($table_name_parent)) {
                $ParentTable = new Table();
                $ParentTable->setTableName($table_name_parent);
            } else {
                $ParentTable = new Table($this->data_source_json[$table_name_parent]);
            }

            $ParentTable->appendChild($table_name, $column_name);
            $this->data_source_json[$table_name_parent] = $ParentTable->get();
        }


        if ($Table->getTableNotFound()) {
            array_push($this->table_not_found, $Table->getTableNotFound());
        }
        $this->data_source_json[$table_name] = $Table->get();
    }

    private function findTable($table_name)
    {
        if (array_key_exists($table_name, $this->data_source_json)) {
            return true;
        }
        return false;
    }

    public function get()
    {
        return [
            'data_source_json' => $this->data_source_json,
            'table_not_found' => $this->table_not_found,
            'result_analysis' => $this->result_analysis
        ];
    }
}
