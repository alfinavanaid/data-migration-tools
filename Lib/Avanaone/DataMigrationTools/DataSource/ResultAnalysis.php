<?php

namespace Lib\Avanaone\DataMigrationTools\DataSource;

class ResultAnalysis
{

    private $data_source_json;

    function __construct($data_source_json)
    {
        $this->data_source_json = $data_source_json;
    }

    function getReports()
    {
        $table_master = [];
        $table_transaction = [];
        $table_standalone = [];
        foreach ($this->data_source_json as $key => $data_source_json) {
            if (count($data_source_json->relations_parent) == 0) {
                if (count($data_source_json->relations_child) > 0) {
                    array_push($table_master, $key);
                } else {
                    array_push($table_standalone, $key);
                }
            } else {
                array_push($table_transaction, $key);
            }
        }
        return [
            'table_master' => $table_master,
            'table_transaction' => $table_transaction,
            'table_standalone' => $table_standalone,
        ];
    }
}
